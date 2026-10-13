<?php

namespace Offlineagency\LaravelWebex\Release;

/**
 * The GitHub REST calls the release tooling needs. Responses are untrusted data: titles, bodies and labels are
 * never evaluated, only copied into JSON request bodies.
 */
interface GitHubApi
{
    /**
     * Every tag of the repository (all pages): tag name => commit SHA.
     *
     * @return array<string, string>
     */
    public function tags(): array;

    /**
     * Every release of the repository (all pages).
     *
     * @return list<array{tag_name: string, body: string}>
     */
    public function releases(): array;

    /**
     * Pull requests associated with a commit.
     *
     * @return list<PullRequest>
     */
    public function pullRequestsForCommit(string $sha): array;

    public function pullRequest(int $number): ?PullRequest;

    /**
     * The state of the latest run of $workflow for a push of $sha on $branch: "success", "pending" while it is not
     * completed, another conclusion ("failure", "cancelled", "skipped", ...) or null when there is no run.
     */
    public function workflowState(string $workflow, string $branch, string $sha): ?string;

    public function createTag(string $tag, string $sha): void;

    public function createRelease(string $tag, string $sha, string $body): void;
}
