<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Report extends AbstractEntity
{
    public ?string $id = null;

    public mixed $templateId = null;

    public mixed $name = null;

    public mixed $description = null;

    public mixed $startTime = null;

    public mixed $endTime = null;

    public ?string $status = null;

    public ?string $created = null;

    public mixed $downloadUrl = null;
}
