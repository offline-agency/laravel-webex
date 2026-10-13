<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Organization extends AbstractEntity
{
    public ?string $id = null;

    public ?string $displayName = null;

    public mixed $name = null;

    public ?string $created = null;

    public mixed $orgPreferences = null;

    public mixed $licenses = null;

    public mixed $preferredGeo = null;
}
