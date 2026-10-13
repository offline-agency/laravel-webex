<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ReportTemplate extends AbstractEntity
{
    public mixed $id = null;

    public mixed $name = null;

    public mixed $description = null;

    public mixed $reportSections = null;

    public mixed $created = null;

    public mixed $updated = null;
}
