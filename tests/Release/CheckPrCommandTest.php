<?php

/**
 * @param  array<string, mixed>  $pullRequest
 * @return array{0: int, 1: string, 2: string}
 */
function runCheckPr(array $pullRequest): array
{
    $event = tempnam(sys_get_temp_dir(), 'event');
    file_put_contents($event, json_encode(['pull_request' => $pullRequest], JSON_THROW_ON_ERROR));
    $process = proc_open([PHP_BINARY, __DIR__.'/../../tools/release/bin/release', 'check-pr', (string) $event], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (! is_resource($process)) {
        throw new RuntimeException('Cannot run the release tool.');
    }
    $stdout = (string) stream_get_contents($pipes[1]);
    $stderr = (string) stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    return [proc_close($process), $stdout, $stderr];
}

it('reports the bump a pull request will get when merged', function () {
    [$code, $stdout] = runCheckPr(['title' => 'feat: rooms export', 'body' => '', 'labels' => []]);

    expect($code)->toBe(0)->and($stdout)->toContain('Release bump if merged: minor (feat title)');
});

it('fails the pre-merge check on contradictory release metadata', function () {
    [$code, , $stderr] = runCheckPr(['title' => 'fix: x', 'body' => '', 'labels' => [['name' => 'release:minor'], ['name' => 'release:major']]]);

    expect($code)->toBe(1)->and($stderr)->toContain('More than one release label');
});

it('treats the title and body as data, never as code', function () {
    [$code, $stdout] = runCheckPr(['title' => '$(touch /tmp/pwned) `id`', 'body' => '"; exit 1; "', 'labels' => []]);

    expect($code)->toBe(0)
        ->and($stdout)->toContain('Release bump if merged: patch')
        ->and(file_exists('/tmp/pwned'))->toBeFalse();
});

it('reports a publishing error as a workflow error with a non-zero exit code', function () {
    $process = proc_open([PHP_BINARY, __DIR__.'/../../tools/release/bin/release', 'publish', '--pr=1'], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, ['GITHUB_TOKEN' => 'test-token', 'GITHUB_API_URL' => 'http://127.0.0.1:9', 'PATH' => (string) getenv('PATH')]);
    if (! is_resource($process)) {
        throw new RuntimeException('Cannot run the release tool.');
    }
    $stdout = (string) stream_get_contents($pipes[1]);
    $stderr = (string) stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    // the API is unreachable (a closed local port, no network): a clean error, no stack trace
    expect(proc_close($process))->toBe(1)
        ->and($stderr)->toStartWith('::error::')
        ->and($stdout.$stderr)->not->toContain('Stack trace');
});

it('refuses to publish without a token', function () {
    $process = proc_open([PHP_BINARY, __DIR__.'/../../tools/release/bin/release', 'publish', '--dry-run'], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, ['PATH' => (string) getenv('PATH')]);
    if (! is_resource($process)) {
        throw new RuntimeException('Cannot run the release tool.');
    }
    $stderr = (string) stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    expect(proc_close($process))->toBe(1)->and($stderr)->toContain('GITHUB_TOKEN is not set.');
});
