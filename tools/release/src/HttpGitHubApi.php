<?php

namespace Offlineagency\LaravelWebex\Release;

use RuntimeException;

/**
 * GitHub REST API over PHP streams (no Composer dependency, so the publishing job installs nothing).
 */
final class HttpGitHubApi implements GitHubApi
{
    public function __construct(
        private readonly string $repository,
        private readonly string $token,
        private readonly string $apiUrl = 'https://api.github.com',
    ) {}

    public function tags(): array
    {
        $tags = [];
        foreach ($this->paginate("/repos/{$this->repository}/tags") as $tag) {
            $commit = is_array($tag['commit'] ?? null) ? $tag['commit'] : [];
            if (is_string($tag['name'] ?? null) && is_string($commit['sha'] ?? null)) {
                $tags[$tag['name']] = $commit['sha'];
            }
        }

        return $tags;
    }

    public function releases(): array
    {
        $releases = [];
        foreach ($this->paginate("/repos/{$this->repository}/releases") as $release) {
            if (is_string($release['tag_name'] ?? null)) {
                $releases[] = ['tag_name' => $release['tag_name'], 'body' => is_string($release['body'] ?? null) ? $release['body'] : ''];
            }
        }

        return $releases;
    }

    public function pullRequestsForCommit(string $sha): array
    {
        $prs = [];
        foreach ($this->paginate("/repos/{$this->repository}/commits/{$sha}/pulls") as $data) {
            $pr = PullRequest::fromApi($data);
            if ($pr !== null) {
                $prs[] = $pr;
            }
        }

        return $prs;
    }

    public function pullRequest(int $number): ?PullRequest
    {
        [$status, $data] = $this->request('GET', "/repos/{$this->repository}/pulls/{$number}");

        return $status === 200 && is_array($data) ? PullRequest::fromApi($data) : null;
    }

    public function workflowState(string $workflow, string $branch, string $sha): ?string
    {
        [, $data] = $this->request('GET', "/repos/{$this->repository}/actions/workflows/{$workflow}/runs?".http_build_query(['head_sha' => $sha, 'branch' => $branch, 'event' => 'push', 'per_page' => 100]));
        $runs = is_array($data) && is_array($data['workflow_runs'] ?? null) ? $data['workflow_runs'] : [];

        $latest = null;
        foreach ($runs as $run) {
            if (is_array($run) && is_int($run['id'] ?? null) && ($latest === null || $run['id'] > $latest['id'])) {
                $latest = $run;
            }
        }
        if ($latest === null) {
            return null;
        }

        return ($latest['status'] ?? null) !== 'completed' ? 'pending' : (is_string($latest['conclusion'] ?? null) ? $latest['conclusion'] : 'unknown');
    }

    public function createTag(string $tag, string $sha): void
    {
        [$status] = $this->request('POST', "/repos/{$this->repository}/git/refs", ['ref' => "refs/tags/{$tag}", 'sha' => $sha]);
        if ($status !== 201) {
            throw new RuntimeException("Creating tag {$tag} failed (HTTP {$status}).");
        }
    }

    public function createRelease(string $tag, string $sha, string $body): void
    {
        [$status] = $this->request('POST', "/repos/{$this->repository}/releases", ['tag_name' => $tag, 'target_commitish' => $sha, 'name' => $tag, 'body' => $body, 'draft' => false, 'prerelease' => false, 'make_latest' => 'legacy']);
        if ($status !== 201) {
            throw new RuntimeException("Creating release {$tag} failed (HTTP {$status}).");
        }
    }

    /**
     * @return list<array<mixed>>
     */
    private function paginate(string $path): array
    {
        $items = [];
        $separator = str_contains($path, '?') ? '&' : '?';
        for ($page = 1; $page <= 100; $page++) {
            [$status, $data] = $this->request('GET', "{$path}{$separator}per_page=100&page={$page}");
            if ($status !== 200 || ! is_array($data)) {
                throw new RuntimeException("GitHub API GET {$path} failed (HTTP {$status}).");
            }
            foreach ($data as $item) {
                if (is_array($item)) {
                    $items[] = $item;
                }
            }
            if (count($data) < 100) {
                return $items;
            }
        }

        throw new RuntimeException("GitHub API GET {$path}: more than 100 pages.");
    }

    /**
     * @param  array<string, mixed>|null  $body
     * @return array{0: int, 1: mixed}
     */
    private function request(string $method, string $path, ?array $body = null): array
    {
        $context = stream_context_create(['http' => [
            'method' => $method,
            'header' => implode("\r\n", [
                'Accept: application/vnd.github+json',
                'Authorization: Bearer '.$this->token,
                'User-Agent: laravel-webex-release',
                'X-GitHub-Api-Version: 2022-11-28',
                'Content-Type: application/json',
            ]),
            'content' => $body === null ? '' : json_encode($body, JSON_THROW_ON_ERROR),
            'ignore_errors' => true,
            'timeout' => 30,
        ]]);
        $response = @file_get_contents($this->apiUrl.$path, false, $context);
        $headers = http_get_last_response_headers() ?? [];
        $status = preg_match('#^HTTP/\S+ (\d{3})#', $headers[0] ?? '', $m) === 1 ? (int) $m[1] : 0;

        return [$status, $response === false || $response === '' ? null : json_decode($response, true)];
    }
}
