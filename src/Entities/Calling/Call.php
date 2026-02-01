<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Call extends AbstractEntity
{
    public $callId;

    public $callSessionId;

    public $state;

    public $direction;

    public $callbackNumber;

    public $callbackSipAddress;

    public $created;

    public $answered;

    public $ended;
}
