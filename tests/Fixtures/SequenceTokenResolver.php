<?php

namespace Offlineagency\LaravelWebex\Tests\Fixtures;

use Offlineagency\LaravelWebex\Contracts\TokenResolver;

/**
 * Hands out the next token of a list on every call (like a refreshed OAuth token).
 */
final class SequenceTokenResolver implements TokenResolver
{
    /** @var list<string> */
    public static array $tokens = [];

    public function token(): string
    {
        return array_shift(self::$tokens) ?? 'exhausted';
    }
}
