<?php

namespace Offlineagency\LaravelWebex\Release;

use RuntimeException;

/**
 * Publishes one stable tag and one GitHub Release per pull request merged into the base branch, after the CI run
 * of its exact merge commit succeeded.
 *
 * Merges are processed in the order they landed on the base branch (first-parent history after the last
 * release made by this tooling, or after `since_commit`), so versions never go backwards:
 *  - a merge whose CI run is still pending stops the run (a later run continues from there);
 *  - a merge whose CI run failed, was cancelled or skipped gets no release of its own; it is listed in the next
 *    release, whose own CI run validated the combined code;
 *  - a pull request already released (marker in a release body) is skipped, a tag already pointing to its merge
 *    commit is completed with its release, and existing tags are never moved (creating a tag that appeared meanwhile
 *    fails).
 */
final class Releaser
{
    /**
     * @var list<string>
     */
    public array $log = [];

    /**
     * @param  array{repository: string, base_branch: string, ci_workflow: string, since_commit: string}  $config
     */
    public function __construct(
        private readonly GitHubApi $github,
        private readonly GitHistory $git,
        private readonly array $config,
    ) {}

    /**
     * The tooling configuration (tools/release/config.json), validated.
     *
     * @return array{repository: string, base_branch: string, ci_workflow: string, since_commit: string}
     */
    public static function loadConfig(string $path): array
    {
        $config = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $keys = ['repository', 'base_branch', 'ci_workflow', 'since_commit'];
        $values = [];
        foreach ($keys as $key) {
            $value = is_array($config) ? ($config[$key] ?? null) : null;
            if (! is_string($value) || $value === '') {
                throw new RuntimeException("Invalid release configuration: {$key} must be a non-empty string.");
            }
            $values[$key] = $value;
        }

        return ['repository' => $values['repository'], 'base_branch' => $values['base_branch'], 'ci_workflow' => $values['ci_workflow'], 'since_commit' => $values['since_commit']];
    }

    /**
     * @param  string  $head  the base branch head in the local clone (for example "origin/main")
     * @param  int|null  $untilPr  stop after this merged pull request (recovery), revalidated through the API
     * @return list<array{pr: int, sha: string, tag: string, created: bool}>
     */
    public function run(string $head, ?int $untilPr, bool $dryRun): array
    {
        $releases = $this->github->releases();
        $releasedPrs = [];
        $start = $this->config['since_commit'];
        foreach ($releases as $release) {
            $marker = ReleaseNotes::marker($release['body']);
            if ($marker !== null) {
                $releasedPrs[$marker['pr']] = $release['tag_name'];
            }
        }
        $tags = $this->github->tags();
        $latest = Version::latest(array_keys($tags)) ?? Version::parse('0.0.0');

        $commits = $this->git->firstParentCommits($start, $head);
        $until = $untilPr === null ? null : $this->mergeCommitOf($untilPr, $commits);

        $published = [];
        $carried = [];
        foreach ($commits as $sha) {
            $pr = $this->pullRequestMergedAt($sha);
            if ($pr === null) {
                $this->log[] = "{$sha}: not the merge commit of a pull request into {$this->config['base_branch']}, skipped";
            } elseif (isset($releasedPrs[$pr->number])) {
                $this->log[] = "#{$pr->number} {$sha}: already released as {$releasedPrs[$pr->number]}";
                $carried = [];
            } else {
                $state = $this->github->workflowState($this->config['ci_workflow'], $this->config['base_branch'], $sha);
                if ($state === null || $state === 'pending') {
                    $this->log[] = "#{$pr->number} {$sha}: CI ".($state ?? 'not started').', stopping until it completes';

                    break;
                }
                if ($state !== 'success') {
                    $this->log[] = "#{$pr->number} {$sha}: CI {$state}, no release of its own (included in the next release)";
                    $carried[] = $pr;
                } else {
                    [$entry, $latest] = $this->publish($pr, $sha, $latest, $tags, $carried, $dryRun);
                    $published[] = $entry;
                    $releasedPrs[$pr->number] = $entry['tag'];
                    $carried = [];
                }
            }

            if ($sha === $until) {
                break;
            }
        }

        return $published;
    }

    /**
     * @param  array<string, string>  $tags
     * @param  list<PullRequest>  $carried
     * @return array{0: array{pr: int, sha: string, tag: string, created: bool}, 1: Version}
     */
    private function publish(PullRequest $pr, string $sha, Version $latest, array $tags, array $carried, bool $dryRun): array
    {
        $decision = BumpPolicy::decide($pr->title, $pr->body, $pr->labels);
        foreach ($carried as $included) {
            $decision = new BumpDecision(Bump::max($decision->bump, BumpPolicy::decide($included->title, $included->body, $included->labels)->bump), $decision->reason, $decision->breaking, $decision->problems);
        }

        // a previous run created the tag on this commit but not the release: complete it
        $existing = array_search($sha, $tags, true);
        if (is_string($existing) && Version::tryParse($existing) !== null) {
            $version = Version::parse($existing);
        } else {
            // the highest stable tag + bump never exists yet; a tag created meanwhile by someone else makes the
            // tag creation fail (the API never overwrites a reference)
            $version = $latest->bump($decision->bump);
        }

        $previous = Version::latest(array_filter(array_keys($tags), fn (string $tag) => Version::tryParse($tag)?->compare($version) < 0)) ?? Version::parse('0.0.0');
        $notes = ReleaseNotes::render($pr, $sha, $previous, $version, $decision, $carried);
        $this->log[] = sprintf('#%d %s: %s %s -> %s (%s)', $pr->number, $sha, $dryRun ? 'would release' : 'releasing', $previous, $version, $decision->reason);
        if (! $dryRun) {
            if (! isset($tags[(string) $version])) {
                $this->github->createTag((string) $version, $sha);
            }
            $this->github->createRelease((string) $version, $sha, $notes);
        }

        return [['pr' => $pr->number, 'sha' => $sha, 'tag' => (string) $version, 'created' => ! $dryRun], $version->compare($latest) > 0 ? $version : $latest];
    }

    /**
     * The merged pull request whose merge commit is $sha on the base branch of this repository.
     */
    private function pullRequestMergedAt(string $sha): ?PullRequest
    {
        foreach ($this->github->pullRequestsForCommit($sha) as $pr) {
            if ($pr->merged && $pr->mergeCommitSha === $sha && $pr->baseRef === $this->config['base_branch'] && $pr->baseRepository === $this->config['repository']) {
                return $pr;
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $commits
     */
    private function mergeCommitOf(int $number, array $commits): string
    {
        $pr = $this->github->pullRequest($number);
        if ($pr === null || ! $pr->merged || $pr->mergeCommitSha === null || $pr->baseRef !== $this->config['base_branch'] || $pr->baseRepository !== $this->config['repository']) {
            throw new RuntimeException("Pull request #{$number} is not merged into {$this->config['repository']}:{$this->config['base_branch']}.");
        }
        if (! in_array($pr->mergeCommitSha, $commits, true)) {
            throw new RuntimeException("The merge commit of #{$number} ({$pr->mergeCommitSha}) is not on the first-parent history of {$this->config['base_branch']} after {$this->config['since_commit']}.");
        }

        return $pr->mergeCommitSha;
    }
}
