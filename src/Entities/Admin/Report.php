<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Report extends AbstractEntity
{
    public $id;

    public $templateId;

    public $name;

    public $description;

    public $startTime;

    public $endTime;

    public $status;

    public $created;

    public $downloadUrl;
}
