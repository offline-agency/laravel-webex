<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class People extends AbstractEntity
{
    public ?string $id = null;

    /** @var array<mixed>|null */
    public ?array $emails = null;

    public ?string $displayName = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $avatar = null;

    public ?string $orgId = null;

    /** @var array<mixed>|null */
    public ?array $roles = null;

    /** @var array<mixed>|null */
    public ?array $licenses = null;

    public ?string $created = null;

    public ?string $lastActivity = null;

    public ?string $status = null;

    public ?string $type = null;

    public ?string $loginEnabled = null;
}
