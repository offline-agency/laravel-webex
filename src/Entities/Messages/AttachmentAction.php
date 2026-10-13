<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class AttachmentAction extends AbstractEntity
{
    public ?string $id = null;

    public ?string $type = null;

    public ?string $messageId = null;

    public ?string $personId = null;

    public mixed $personEmail = null;

    public ?string $created = null;

    public mixed $inputs = null;
}
