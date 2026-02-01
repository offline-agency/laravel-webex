<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class AttachmentAction extends AbstractEntity
{
    public $id;

    public $type;

    public $messageId;

    public $personId;

    public $personEmail;

    public $created;

    public $inputs;
}
