<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class TeamMembership extends AbstractEntity
{
    public $id;

    public $teamId;

    public $personId;

    public $personEmail;

    public $personDisplayName;

    public $isModerator;

    public $created;
}
