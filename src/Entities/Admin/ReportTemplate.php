<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ReportTemplate extends AbstractEntity
{
    public $id;

    public $name;

    public $description;

    public $reportSections;

    public $created;

    public $updated;
}
