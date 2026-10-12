<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class AttachmentAction extends AbstractEntity
{
    public mixed $id = null;

    public mixed $type = null;

    public mixed $messageId = null;

    public mixed $personId = null;

    public mixed $personEmail = null;

    public mixed $created = null;

    public mixed $inputs = null;
}
