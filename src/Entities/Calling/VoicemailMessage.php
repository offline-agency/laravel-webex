<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class VoicemailMessage extends AbstractEntity
{
    public $id;

    public $userId;

    public $from;

    public $duration;

    public $created;

    public $read;

    public $mediaUrl;
}
