<?php

namespace Offlineagency\LaravelWebex\Release;

enum Bump: int
{
    case Patch = 1;
    case Minor = 2;
    case Major = 3;

    public static function max(self $a, self $b): self
    {
        return $a->value >= $b->value ? $a : $b;
    }

    public function label(): string
    {
        return strtolower($this->name);
    }
}
