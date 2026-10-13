<?php

namespace Offlineagency\LaravelWebex\Exceptions;

use Offlineagency\LaravelWebex\Entities\Error;

/**
 * A request made while iterating a cursor failed; $error is the Error the non-lazy method would have returned.
 */
final class WebexRequestException extends WebexException
{
    public function __construct(public readonly Error $error)
    {
        parent::__construct('Webex request failed: '.$error->message);
    }
}
