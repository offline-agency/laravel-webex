<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Organization extends AbstractEntity
{
    public mixed $id = null;

    public mixed $displayName = null;

    public mixed $name = null;

    public mixed $created = null;

    public mixed $orgPreferences = null;

    public mixed $licenses = null;

    public mixed $preferredGeo = null;
}
