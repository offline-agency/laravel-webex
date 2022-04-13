<?php

namespace Offlineagency\LaravelWebex\Entities;

class Error extends AbstractEntity
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var array
     */
    public $errors;

    /**
     * @var string
     */
    public $trackingId;
}
