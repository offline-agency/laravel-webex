<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class TeamMembership extends AbstractEntity
{
    public ?string $id = null;

    public ?string $teamId = null;

    public ?string $personId = null;

    public ?string $personEmail = null;

    public ?string $personDisplayName = null;

    public ?bool $isModerator = null;

    public ?string $created = null;
}
