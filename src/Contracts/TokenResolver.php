<?php

namespace Offlineagency\LaravelWebex\Contracts;

/**
 * Supplies the bearer token for each Webex request (configured as webex.token_resolver): use it to refresh OAuth
 * tokens in long-running processes (queue workers, Octane) without recreating the client.
 */
interface TokenResolver
{
    public function token(): string;
}
