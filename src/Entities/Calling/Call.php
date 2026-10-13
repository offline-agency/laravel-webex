<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Call extends AbstractEntity
{
    public ?string $callId = null;

    public ?string $callSessionId = null;

    public mixed $state = null;

    public mixed $direction = null;

    public mixed $callbackNumber = null;

    public mixed $callbackSipAddress = null;

    public ?string $created = null;

    public ?string $answered = null;

    public mixed $ended = null;
}
