<?php

namespace Offlineagency\LaravelWebex\Release;

use InvalidArgumentException;

/**
 * A stable SemVer version written without prefix, as the existing tags (1.0.0, 1.1.0).
 */
final class Version
{
    private function __construct(public readonly int $major, public readonly int $minor, public readonly int $patch) {}

    public static function tryParse(string $tag): ?self
    {
        if (preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/', $tag, $m) !== 1) {
            return null;
        }

        return new self((int) $m[1], (int) $m[2], (int) $m[3]);
    }

    public static function parse(string $tag): self
    {
        return self::tryParse($tag) ?? throw new InvalidArgumentException("Not a stable version: {$tag}");
    }

    public function bump(Bump $bump): self
    {
        return match ($bump) {
            Bump::Major => new self($this->major + 1, 0, 0),
            Bump::Minor => new self($this->major, $this->minor + 1, 0),
            Bump::Patch => new self($this->major, $this->minor, $this->patch + 1),
        };
    }

    public function compare(self $other): int
    {
        return [$this->major, $this->minor, $this->patch] <=> [$other->major, $other->minor, $other->patch];
    }

    /**
     * The highest stable version among $tags (prereleases and unrelated tags are ignored).
     *
     * @param  iterable<string>  $tags
     */
    public static function latest(iterable $tags): ?self
    {
        $latest = null;
        foreach ($tags as $tag) {
            $version = self::tryParse($tag);
            if ($version !== null && ($latest === null || $version->compare($latest) > 0)) {
                $latest = $version;
            }
        }

        return $latest;
    }

    public function __toString(): string
    {
        return "{$this->major}.{$this->minor}.{$this->patch}";
    }
}
