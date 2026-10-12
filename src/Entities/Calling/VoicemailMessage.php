<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class VoicemailMessage extends AbstractEntity
{
    public mixed $id = null;

    public mixed $userId = null;

    public mixed $from = null;

    public mixed $duration = null;

    public mixed $created = null;

    public mixed $read = null;

    public mixed $mediaUrl = null;
}
