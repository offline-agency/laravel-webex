<?php

namespace Offlineagency\LaravelWebex\Entities;

class Error extends AbstractEntity
{
    public ?string $message = null;

    /** @var array<int, mixed>|null */
    public ?array $errors = null;

    public ?string $trackingId = null;

    public function build(array $parameters): void
    {
        parent::build($parameters);

        if ($this->message === null) {
            $this->message = 'Unknown error';
        }
        if ($this->errors === null) {
            $this->errors = [];
        }
    }
}
