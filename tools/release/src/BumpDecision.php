<?php

namespace Offlineagency\LaravelWebex\Release;

final class BumpDecision
{
    /**
     * @param  list<string>  $problems  contradictions that fail the pre-merge check
     */
    public function __construct(
        public readonly Bump $bump,
        public readonly string $reason,
        public readonly bool $breaking,
        public readonly array $problems,
    ) {}
}
