<?php

use Offlineagency\LaravelWebex\Release\Bump;
use Offlineagency\LaravelWebex\Release\BumpPolicy;
use Offlineagency\LaravelWebex\Release\GitHistory;
use Offlineagency\LaravelWebex\Release\ReleaseNotes;
use Offlineagency\LaravelWebex\Release\Releaser;
use Offlineagency\LaravelWebex\Release\Version;
use Offlineagency\LaravelWebex\Tests\Release\FakeGitHubApi;
use Offlineagency\LaravelWebex\Tests\Release\GitRepository;

/**
 * @return array{0: FakeGitHubApi, 1: GitRepository, 2: Closure(?int=, bool=): array<int, array{pr: int, sha: string, tag: string, created: bool}>}
 */
function releaseFixture(): array
{
    $github = new FakeGitHubApi;
    $github->tags = ['1.0.0' => str_repeat('a', 40), '1.1.0' => str_repeat('b', 40), 'v9.9.9' => str_repeat('c', 40), '3.0.0-beta.1' => str_repeat('d', 40)];
    $repo = new GitRepository;
    $run = function (?int $pr = null, bool $dryRun = false) use ($github, $repo) {
        $releaser = new Releaser($github, new GitHistory($repo->path), [
            'repository' => 'offline-agency/laravel-webex', 'base_branch' => 'main', 'ci_workflow' => 'main.yml', 'since_commit' => $repo->root,
        ]);

        return $releaser->run('main', $pr, $dryRun);
    };

    return [$github, $repo, $run];
}

describe('versions and bump policy', function () {
    it('takes the highest stable tag, ignoring prereleases and unrelated tags', function () {
        expect((string) Version::latest(['1.0.0', '1.10.0', '1.9.0', 'v2.0.0', '2.0.0-rc.1', 'latest']))->toBe('1.10.0')
            ->and(Version::latest(['nightly']))->toBeNull();
    });

    it('bumps from the release label, the Conventional Commit title or the patch default', function (string $title, string $body, array $labels, Bump $bump) {
        expect(BumpPolicy::decide($title, $body, array_values(array_filter($labels, 'is_string')))->bump)->toBe($bump);
    })->with([
        'feature' => ['feat: add rooms export', '', [], Bump::Minor],
        'fix' => ['fix: handle 201', '', [], Bump::Patch],
        'docs only' => ['docs: readme', '', [], Bump::Patch],
        'ci only' => ['ci: pin actions', '', [], Bump::Patch],
        'unclassified' => ['Develop', '', [], Bump::Patch],
        'breaking title' => ['feat!: drop Laravel 11', '', [], Bump::Major],
        'breaking footer' => ['refactor: rename', "text\n\nBREAKING CHANGE: Messages::delete() is destroy()", [], Bump::Major],
        'label wins over title' => ['fix: x', '', ['release:minor'], Bump::Minor],
        'major label' => ['Develop', '', ['release:major'], Bump::Major],
    ]);

    it('reports contradictory release metadata', function (array $labels, string $title, string $problem) {
        expect(BumpPolicy::decide($title, '', array_values(array_filter($labels, 'is_string')))->problems)->toContain($problem);
    })->with([
        'two labels' => [['release:minor', 'release:patch'], 'fix: x', 'More than one release label: use exactly one of release:major, release:minor, release:patch.'],
        'breaking without major' => [['release:patch'], 'feat!: x', 'The pull request is marked as breaking but its release label is not release:major.'],
    ]);
});

describe('publishing', function () {
    it('releases a merge into main whose CI passed, at its exact merge commit', function () {
        [$github, $repo, $run] = releaseFixture();
        $sha = $repo->commit('Merge pull request #13');
        $github->merged(13, $sha, 'Develop', ['release:major'], "Laravel 12/13\n\n## Migration\n- Messages::delete() is destroy()");
        $github->ci[$sha] = 'success';

        $published = $run();

        expect($published)->toBe([['pr' => 13, 'sha' => $sha, 'tag' => '2.0.0', 'created' => true]])
            ->and($github->writes)->toBe(["tag 2.0.0 {$sha}", "release 2.0.0 {$sha}"])
            ->and(ReleaseNotes::marker($github->releases[0]['body']))->toBe(['pr' => 13, 'sha' => $sha])
            ->and($github->releases[0]['body'])->toContain('previous version: 1.1.0', 'Messages::delete() is destroy()', 'Breaking changes and migration', '/pull/13');
    });

    it('publishes nothing for commits that are not merges of pull requests into main', function () {
        [$github, $repo, $run] = releaseFixture();
        $direct = $repo->commit('direct push');
        $intoDevelop = $repo->commit('merge into develop');
        $closed = $repo->commit('closed without merge');
        $github->merged(20, $intoDevelop, 'feat: x', base: 'develop');
        $github->merged(21, $closed, 'feat: y', merged: false);
        $github->merged(22, str_repeat('e', 40), 'feat: other repository', repository: 'someone/fork');
        $github->ci = [$direct => 'success', $intoDevelop => 'success', $closed => 'success'];

        expect($run())->toBe([])->and($github->writes)->toBe([]);
    });

    it('releases merged fork pull requests like any other (the base repository is what counts)', function () {
        [$github, $repo, $run] = releaseFixture();
        $sha = $repo->commit('Merge pull request #30 from contributor/fork');
        $github->merged(30, $sha, 'fix: typo in README');
        $github->ci[$sha] = 'success';

        expect($run()[0]['tag'])->toBe('1.1.1');
    });

    it('applies minor and patch bumps in merge order', function () {
        [$github, $repo, $run] = releaseFixture();
        foreach ([[40, 'feat: a'], [41, 'fix: b'], [42, 'docs: c']] as [$number, $title]) {
            $sha = $repo->commit($title);
            $github->merged($number, $sha, $title);
            $github->ci[$sha] = 'success';
        }

        expect(array_column($run(), 'tag'))->toBe(['1.2.0', '1.2.1', '1.2.2']);
    });

    it('does not release a merge whose CI failed, was cancelled or skipped, and ships it in the next release', function (string $state) {
        [$github, $repo, $run] = releaseFixture();
        $bad = $repo->commit('broken');
        $good = $repo->commit('fixed');
        $github->merged(50, $bad, 'feat!: broken change');
        $github->merged(51, $good, 'fix: repair');
        $github->ci = [$bad => $state, $good => 'success'];

        $published = $run();

        expect($published)->toHaveCount(1)
            ->and($published[0])->toMatchArray(['pr' => 51, 'sha' => $good, 'tag' => '2.0.0'])
            ->and($github->releases[0]['body'])->toContain('#50', 'not released separately');
    })->with(['failure', 'cancelled', 'skipped']);

    it('stops at a merge whose CI is pending and continues in order once it completes', function () {
        [$github, $repo, $run] = releaseFixture();
        $shas = [];
        foreach ([60, 61, 62] as $number) {
            $shas[$number] = $repo->commit("merge #{$number}");
            $github->merged($number, $shas[$number], "fix: change {$number}");
        }
        // three quick merges; CI finishes #60 and #62 before #61
        $github->ci = [$shas[60] => 'success', $shas[61] => 'pending', $shas[62] => 'success'];
        expect(array_column($run(), 'tag'))->toBe(['1.1.1']);

        $github->ci[$shas[61]] = 'success';
        expect(array_column($run(), 'pr'))->toBe([61, 62])
            ->and(array_map(fn (array $release) => ReleaseNotes::marker($release['body']), $github->releases))
            ->toBe([['pr' => 60, 'sha' => $shas[60]], ['pr' => 61, 'sha' => $shas[61]], ['pr' => 62, 'sha' => $shas[62]]])
            ->and(array_keys(array_filter($github->tags, fn (string $sha) => in_array($sha, $shas, true))))->toBe(['1.1.1', '1.1.2', '1.1.3']);
    });

    it('is a no-op when everything is already released', function () {
        [$github, $repo, $run] = releaseFixture();
        $sha = $repo->commit('merge');
        $github->merged(70, $sha, 'feat: x');
        $github->ci[$sha] = 'success';
        $run();
        $writes = $github->writes;

        expect($run())->toBe([])->and($github->writes)->toBe($writes);
    });

    it('completes a release whose tag was created by a failed previous run', function () {
        [$github, $repo, $run] = releaseFixture();
        $sha = $repo->commit('merge');
        $github->merged(80, $sha, 'feat: x');
        $github->ci[$sha] = 'success';
        $github->failReleaseOnce = true;

        expect(fn () => $run())->toThrow(RuntimeException::class, 'GitHub API error while creating the release');
        expect($github->tags['1.2.0'])->toBe($sha);

        expect($run())->toBe([['pr' => 80, 'sha' => $sha, 'tag' => '1.2.0', 'created' => true]])
            ->and($github->writes)->toBe(["tag 1.2.0 {$sha}", "release 1.2.0 {$sha}"]);
    });

    it('fails clearly and moves nothing when the next tag appears on another commit meanwhile', function () {
        [$github, $repo, $run] = releaseFixture();
        $sha = $repo->commit('merge');
        $github->merged(90, $sha, 'feat: x');
        $github->ci[$sha] = 'success';
        $github->concurrentTags['1.2.0'] = str_repeat('f', 40);

        expect(fn () => $run())->toThrow(RuntimeException::class, 'Creating tag 1.2.0 failed (HTTP 422).')
            ->and($github->writes)->toBe([])
            ->and($github->releases)->toBe([]);
    });

    it('refuses a listed tag with the next version on another commit', function () {
        [$github, $repo, $run] = releaseFixture();
        $sha = $repo->commit('merge');
        $github->merged(91, $sha, 'feat: x');
        $github->ci[$sha] = 'success';
        // a release made by this tooling records which commit 1.2.0 belongs to; the listed tag points elsewhere
        $github->tags = ['1.1.0' => str_repeat('b', 40)];
        $github->releases[] = ['tag_name' => '1.1.0', 'body' => ''];
        $github->concurrentTags = [];
        $github->tags['1.2.0'] = str_repeat('f', 40);
        $github->tags['1.1.9'] = str_repeat('e', 40);

        expect($run()[0]['tag'])->toBe('1.3.0')
            ->and($github->tags['1.2.0'])->toBe(str_repeat('f', 40));
    });

    it('plans without writing in dry-run mode and recovers a given merged pull request', function () {
        [$github, $repo, $run] = releaseFixture();
        $first = $repo->commit('first');
        $second = $repo->commit('second');
        $github->merged(100, $first, 'feat: first');
        $github->merged(101, $second, 'fix: second');
        $github->ci = [$first => 'success', $second => 'success'];

        expect($run(100, true))->toBe([['pr' => 100, 'sha' => $first, 'tag' => '1.2.0', 'created' => false]])
            ->and($github->writes)->toBe([]);

        expect(array_column($run(100), 'tag'))->toBe(['1.2.0'])
            ->and($github->writes)->toBe(["tag 1.2.0 {$first}", "release 1.2.0 {$first}"]);
    });

    it('rejects a recovery request for a pull request that is not merged into main', function (Closure $setup, string $message) {
        [$github, $repo, $run] = releaseFixture();
        $setup($github, $repo);

        expect(fn () => $run(110))->toThrow(RuntimeException::class, $message)
            ->and($github->writes)->toBe([]);
    })->with([
        'unknown' => [fn () => null, 'Pull request #110 is not merged'],
        'not merged' => [fn (FakeGitHubApi $github) => $github->merged(110, str_repeat('1', 40), 'x', merged: false), 'Pull request #110 is not merged'],
        'merged into develop' => [fn (FakeGitHubApi $github) => $github->merged(110, str_repeat('1', 40), 'x', base: 'develop'), 'Pull request #110 is not merged'],
        'merge commit not on main' => [fn (FakeGitHubApi $github) => $github->merged(110, str_repeat('1', 40), 'x'), 'is not on the first-parent history'],
    ]);
});
