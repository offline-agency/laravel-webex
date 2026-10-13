<?php

namespace Offlineagency\LaravelWebex\Release;

/**
 * The fields of a pull request the release tooling reads (validated when decoded).
 */
final class PullRequest
{
    /**
     * @param  list<string>  $labels
     */
    public function __construct(
        public readonly int $number,
        public readonly string $title,
        public readonly string $body,
        public readonly array $labels,
        public readonly string $baseRef,
        public readonly string $baseRepository,
        public readonly bool $merged,
        public readonly ?string $mergeCommitSha,
        public readonly string $url,
    ) {}

    /**
     * @param  array<mixed>  $data  a pull request object of the GitHub REST API
     */
    public static function fromApi(array $data): ?self
    {
        $number = $data['number'] ?? null;
        $base = $data['base'] ?? null;
        $baseRepo = is_array($base) ? ($base['repo'] ?? null) : null;
        if (! is_int($number) || ! is_array($base) || ! is_string($base['ref'] ?? null) || ! is_array($baseRepo) || ! is_string($baseRepo['full_name'] ?? null)) {
            return null;
        }

        $labels = [];
        foreach (is_array($data['labels'] ?? null) ? $data['labels'] : [] as $label) {
            if (is_array($label) && is_string($label['name'] ?? null)) {
                $labels[] = $label['name'];
            }
        }
        $mergeSha = $data['merge_commit_sha'] ?? null;

        return new self(
            $number,
            is_string($data['title'] ?? null) ? $data['title'] : '',
            is_string($data['body'] ?? null) ? $data['body'] : '',
            $labels,
            $base['ref'],
            $baseRepo['full_name'],
            is_string($data['merged_at'] ?? null),
            is_string($mergeSha) ? $mergeSha : null,
            is_string($data['html_url'] ?? null) ? $data['html_url'] : '',
        );
    }
}
