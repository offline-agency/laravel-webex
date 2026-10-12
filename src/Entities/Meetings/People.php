<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class People extends AbstractEntity
{
    public mixed $id = null;

    public mixed $emails = null;

    public mixed $displayName = null;

    public mixed $firstName = null;

    public mixed $lastName = null;

    public mixed $avatar = null;

    public mixed $orgId = null;

    public mixed $roles = null;

    public mixed $licenses = null;

    public mixed $created = null;

    public mixed $lastActivity = null;

    public mixed $status = null;

    public mixed $type = null;

    public mixed $loginEnabled = null;
}
