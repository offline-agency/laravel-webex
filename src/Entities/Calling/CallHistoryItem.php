<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class CallHistoryItem extends AbstractEntity
{
    public mixed $callId = null;

    public mixed $callSessionId = null;

    public mixed $state = null;

    public mixed $direction = null;

    public mixed $created = null;

    public mixed $answered = null;

    public mixed $ended = null;

    public mixed $duration = null;
}
