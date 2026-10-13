<?php

namespace Offlineagency\LaravelWebex\Release;

use RuntimeException;

/**
 * Read-only queries on a local clone (fetch-depth 0). Commands run without a shell (argument arrays).
 */
final class GitHistory
{
    public function __construct(private readonly string $directory) {}

    /**
     * First-parent commits of $until after $since, oldest first: the order in which merges landed on the branch.
     *
     * @return list<string>
     */
    public function firstParentCommits(string $since, string $until): array
    {
        $output = $this->git(['rev-list', '--first-parent', '--reverse', $since.'..'.$until]);

        return array_values(array_filter(explode("\n", trim($output)), fn (string $line) => $line !== ''));
    }

    public function resolve(string $revision): string
    {
        return trim($this->git(['rev-parse', '--verify', $revision.'^{commit}']));
    }

    /**
     * @param  list<string>  $arguments
     */
    private function git(array $arguments): string
    {
        $process = proc_open(['git', '-C', $this->directory, ...$arguments], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (! is_resource($process)) {
            throw new RuntimeException('Cannot run git.');
        }
        $output = (string) stream_get_contents($pipes[1]);
        $error = (string) stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            throw new RuntimeException('git '.implode(' ', $arguments).' failed: '.trim($error));
        }

        return $output;
    }
}
