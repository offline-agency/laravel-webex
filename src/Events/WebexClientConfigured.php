<?php

namespace Offlineagency\LaravelWebex\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * A LaravelWebex client was configured (base URL, timeout, token source). It does not mean that Webex accepted
 * the token: a rejected token is reported as an Error (HTTP 401) by the first request.
 */
class WebexClientConfigured
{
    use Dispatchable;
}
