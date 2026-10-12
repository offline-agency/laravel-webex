<?php

namespace Offlineagency\LaravelWebex\Tests\Release;

/**
 * A disposable local git repository whose branch "main" receives one commit per merged pull request.
 */
final class GitRepository
{
    public readonly string $path;

    public readonly string $root;

    public function __construct()
    {
        $this->path = sys_get_temp_dir().'/webex-release-'.bin2hex(random_bytes(6));
        mkdir($this->path);
        $this->git('init', '-q', '-b', 'main');
        $this->git('config', 'user.email', 'test@example.com');
        $this->git('config', 'user.name', 'Release test');
        $this->root = $this->commit('initial');
    }

    public function commit(string $message): string
    {
        $this->git('commit', '-q', '--allow-empty', '-m', $message);

        return trim($this->git('rev-parse', 'HEAD'));
    }

    public function git(string ...$arguments): string
    {
        $process = proc_open(array_merge(['git', '-C', $this->path], array_values($arguments)), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (! is_resource($process)) {
            throw new \RuntimeException('Cannot run git.');
        }
        $output = (string) stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return $output;
    }
}
