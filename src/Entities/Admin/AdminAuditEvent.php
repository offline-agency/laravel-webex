<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class AdminAuditEvent extends AbstractEntity
{
    public mixed $id = null;

    public mixed $actorId = null;

    public mixed $actorOrgId = null;

    public mixed $application = null;

    public mixed $event = null;

    public mixed $orgId = null;

    public mixed $created = null;

    public mixed $targets = null;

    public mixed $context = null;
}
