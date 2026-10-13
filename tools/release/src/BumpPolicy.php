<?php

namespace Offlineagency\LaravelWebex\Release;

/**
 * Bump of a merged pull request:
 *  1. a "release:major|minor|patch" label, when present (two different release labels are contradictory);
 *  2. otherwise the Conventional Commit type of the title: "feat" -> minor, anything else -> patch;
 *  3. a breaking marker ("type!:" in the title or "BREAKING CHANGE" in the body) always means major, and a
 *     release label lower than major on a breaking change is contradictory.
 * Documentation, CI and dependency-only pull requests therefore release a patch.
 */
final class BumpPolicy
{
    /**
     * @param  list<string>  $labels
     */
    public static function decide(string $title, string $body, array $labels): BumpDecision
    {
        $problems = [];
        $fromLabels = [];
        foreach ($labels as $label) {
            if (preg_match('/^release:(major|minor|patch)$/', $label, $m) === 1) {
                $fromLabels[] = Bump::from(['patch' => 1, 'minor' => 2, 'major' => 3][$m[1]]);
            }
        }
        $fromLabels = array_values(array_unique($fromLabels, SORT_REGULAR));
        if (count($fromLabels) > 1) {
            $problems[] = 'More than one release label: use exactly one of release:major, release:minor, release:patch.';
        }

        $breaking = preg_match('/^[a-z]+(\([^)]*\))?!:/i', $title) === 1 || preg_match('/^BREAKING[ -]CHANGE:/m', $body) === 1;
        $fromTitle = preg_match('/^feat(\([^)]*\))?!?:/i', $title) === 1 ? Bump::Minor : Bump::Patch;

        if ($fromLabels !== []) {
            $bump = array_reduce($fromLabels, fn (Bump $carry, Bump $item) => Bump::max($carry, $item), Bump::Patch);
            $reason = 'label release:'.$bump->label();
        } else {
            $bump = $fromTitle;
            $reason = $fromTitle === Bump::Minor ? 'feat title' : 'default patch';
        }
        if ($breaking) {
            if ($fromLabels !== [] && $bump !== Bump::Major) {
                $problems[] = 'The pull request is marked as breaking but its release label is not release:major.';
            }
            $bump = Bump::Major;
            $reason = 'breaking change';
        }

        return new BumpDecision($bump, $reason, $breaking, $problems);
    }
}
