<?php

namespace Offlineagency\LaravelWebex\Tests\Release;

use Offlineagency\LaravelWebex\Release\GitHubApi;
use Offlineagency\LaravelWebex\Release\PullRequest;

/**
 * In-memory GitHub: pull requests by merge commit, CI states, tags and releases; records every write.
 */
final class FakeGitHubApi implements GitHubApi
{
    /** @var array<string, string> */
    public array $tags = [];

    /** @var list<array{tag_name: string, body: string}> */
    public array $releases = [];

    /** @var array<int, PullRequest> */
    public array $pulls = [];

    /** @var array<string, string> */
    public array $ci = [];

    /** @var list<string> */
    public array $writes = [];

    public bool $failReleaseOnce = false;

    /**
     * Tags created by someone else after the tag list was read (not returned by tags()).
     *
     * @var array<string, string>
     */
    public array $concurrentTags = [];

    public function tags(): array
    {
        return $this->tags;
    }

    public function releases(): array
    {
        return $this->releases;
    }

    public function pullRequestsForCommit(string $sha): array
    {
        return array_values(array_filter($this->pulls, fn (PullRequest $pr) => $pr->mergeCommitSha === $sha));
    }

    public function pullRequest(int $number): ?PullRequest
    {
        return $this->pulls[$number] ?? null;
    }

    public function workflowState(string $workflow, string $branch, string $sha): ?string
    {
        return $this->ci[$sha] ?? null;
    }

    public function createTag(string $tag, string $sha): void
    {
        // like POST /git/refs: an existing reference is never overwritten (HTTP 422)
        if (isset($this->concurrentTags[$tag]) || isset($this->tags[$tag])) {
            throw new \RuntimeException("Creating tag {$tag} failed (HTTP 422).");
        }
        $this->writes[] = "tag {$tag} {$sha}";
        $this->tags[$tag] = $sha;
    }

    public function createRelease(string $tag, string $sha, string $body): void
    {
        if ($this->failReleaseOnce) {
            $this->failReleaseOnce = false;
            throw new \RuntimeException('GitHub API error while creating the release');
        }
        $this->writes[] = "release {$tag} {$sha}";
        $this->releases[] = ['tag_name' => $tag, 'body' => $body];
    }

    /**
     * @param  list<string>  $labels
     */
    public function merged(int $number, string $sha, string $title, array $labels = [], string $body = '', string $base = 'main', string $repository = 'offline-agency/laravel-webex', bool $merged = true): void
    {
        $this->pulls[$number] = new PullRequest($number, $title, $body, $labels, $base, $repository, $merged, $sha, "https://github.com/{$repository}/pull/{$number}");
    }
}
