<?php

namespace Offlineagency\LaravelWebex\Release;

final class ReleaseNotes
{
    public const MARKER = '<!-- auto-release pr=%d sha=%s -->';

    /**
     * @param  list<PullRequest>  $includedWithoutRelease  merged pull requests whose own validation did not pass, shipped in this release
     */
    public static function render(PullRequest $pr, string $sha, Version $previous, Version $next, BumpDecision $decision, array $includedWithoutRelease): string
    {
        $lines = [
            sprintf(self::MARKER, $pr->number, $sha),
            "## {$next}",
            '',
            "Released automatically from [#{$pr->number}]({$pr->url}) at commit `{$sha}` (previous version: {$previous}).",
            "Version bump: **{$decision->bump->label()}** ({$decision->reason}).",
            '',
            "### #{$pr->number}: ".self::inline($pr->title),
            '',
            $pr->body === '' ? '_No description._' : $pr->body,
        ];
        if ($decision->breaking || $decision->bump === Bump::Major) {
            $lines = [...$lines, '', '### Breaking changes and migration', '', "This release contains breaking changes: read the description above and [UPGRADE.md](../../blob/{$sha}/UPGRADE.md)."];
        }
        if ($includedWithoutRelease !== []) {
            $lines = [...$lines, '', '### Also included', ''];
            foreach ($includedWithoutRelease as $included) {
                $lines[] = "- [#{$included->number}]({$included->url}) ".self::inline($included->title).' (its own validation did not pass, so it was not released separately)';
            }
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * The pull request and commit a release body was created for, or null for releases not made by this tooling.
     *
     * @return array{pr: int, sha: string}|null
     */
    public static function marker(string $body): ?array
    {
        if (preg_match('/<!-- auto-release pr=(\d+) sha=([0-9a-f]{40}) -->/', $body, $m) !== 1) {
            return null;
        }

        return ['pr' => (int) $m[1], 'sha' => $m[2]];
    }

    private static function inline(string $text): string
    {
        return trim((string) preg_replace('/\s+/', ' ', $text));
    }
}
