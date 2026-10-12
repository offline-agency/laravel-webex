<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Call extends AbstractEntity
{
    public mixed $callId = null;

    public mixed $callSessionId = null;

    public mixed $state = null;

    public mixed $direction = null;

    public mixed $callbackNumber = null;

    public mixed $callbackSipAddress = null;

    public mixed $created = null;

    public mixed $answered = null;

    public mixed $ended = null;
}
