<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class AdminAuditEvent extends AbstractEntity
{
    public ?string $id = null;

    public ?string $actorId = null;

    public ?string $actorOrgId = null;

    public mixed $application = null;

    public mixed $event = null;

    public mixed $orgId = null;

    public ?string $created = null;

    public mixed $targets = null;

    public mixed $context = null;
}
