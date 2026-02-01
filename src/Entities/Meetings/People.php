<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class People extends AbstractEntity
{
    public $id;

    public $emails;

    public $displayName;

    public $firstName;

    public $lastName;

    public $avatar;

    public $orgId;

    public $roles;

    public $licenses;

    public $created;

    public $lastActivity;

    public $status;

    public $type;

    public $loginEnabled;
}
