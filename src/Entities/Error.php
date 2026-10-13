<?php

namespace Offlineagency\LaravelWebex\Entities;

class Error extends AbstractEntity
{
    public ?string $message = null;

    /** @var array<int, mixed>|null */
    public ?array $errors = null;

    public ?string $trackingId = null;

    /**
     * HTTP status of the failed response; null for network failures and invalid successful responses.
     */
    public ?int $status = null;

    /**
     * Seconds Webex asked to wait before retrying (Retry-After), when it said so.
     */
    public ?int $retryAfter = null;

    /**
     * @param  array<mixed>  $parameters
     */
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
