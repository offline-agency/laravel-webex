<?php

/*
 * Builds tests/Fixtures/webex-operations.json from the official Webex OpenAPI specifications
 * (https://github.com/webex/webex-openapi-specs, public-spec/*.json), so contract tests can check every request
 * the package sends without shipping 15 MB of specifications.
 *
 *   php tools/webex-spec/extract.php <directory with the public-spec json files> <commit>
 */

[, $directory, $commit] = array_pad($argv, 3, '');
if (! is_dir($directory) || preg_match('/^[0-9a-f]{40}$/', $commit) !== 1) {
    fwrite(STDERR, "Usage: php tools/webex-spec/extract.php <public-spec directory> <40-char commit>\n");
    exit(2);
}

$operations = [];
$files = glob(rtrim($directory, '/').'/webex-*.json') ?: [];
sort($files);
foreach ($files as $file) {
    $spec = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
    foreach (is_array($spec['paths'] ?? null) ? $spec['paths'] : [] as $path => $methods) {
        foreach (is_array($methods) ? array_keys($methods) : [] as $method) {
            if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'], true)) {
                // path parameters are normalised to {} and trailing slashes removed
                $operations[] = strtoupper($method).' '.rtrim((string) preg_replace('/\{[^}]+\}/', '{}', (string) $path), '/');
            }
        }
    }
}
$operations = array_values(array_unique($operations));
sort($operations);

file_put_contents(__DIR__.'/../../tests/Fixtures/webex-operations.json', json_encode([
    'source' => [
        'repository' => 'https://github.com/webex/webex-openapi-specs',
        'commit' => $commit,
        'files' => array_map('basename', $files),
    ],
    'operations' => $operations,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

echo count($operations)." operations\n";
