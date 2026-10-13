<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class VoicemailMessage extends AbstractEntity
{
    public ?string $id = null;

    public mixed $userId = null;

    public mixed $from = null;

    public int|float|null $duration = null;

    public ?string $created = null;

    public ?bool $read = null;

    public mixed $mediaUrl = null;
}
