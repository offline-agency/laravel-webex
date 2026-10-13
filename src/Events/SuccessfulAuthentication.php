<?php

namespace Offlineagency\LaravelWebex\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * @deprecated 2.0 dispatched right after WebexClientConfigured, whose name says what happened: the client was
 *             configured, Webex did not confirm the token. Listen to WebexClientConfigured instead.
 */
class SuccessfulAuthentication
{
    use Dispatchable;
}
