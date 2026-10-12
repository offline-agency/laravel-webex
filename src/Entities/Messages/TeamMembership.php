<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class TeamMembership extends AbstractEntity
{
    public mixed $id = null;

    public mixed $teamId = null;

    public mixed $personId = null;

    public mixed $personEmail = null;

    public mixed $personDisplayName = null;

    public mixed $isModerator = null;

    public mixed $created = null;
}
