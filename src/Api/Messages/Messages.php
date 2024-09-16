<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Messages as MessagesEntity;

class Messages extends AbstractApi
{
    public function list(
        string $roomId,
        ?array $additional_data = []
    ) {
        $response = $this->get('messages', [


        ]);
    }
}
