<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class CallHistoryItem extends AbstractEntity
{
    public $callId;

    public $callSessionId;

    public $state;

    public $direction;

    public $created;

    public $answered;

    public $ended;

    public $duration;
}
