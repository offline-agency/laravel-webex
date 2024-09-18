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
        $additional_data = $this->data($additional_data, [
            'parentId', 'mentionedPeople', 'before', 'beforeMessage', 'max',
        ]);

        $response = $this->get('messages', array_merge([
            'roomId' => $roomId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $messages = $response->data;

        return array_map(function ($message) {
            return new MessagesEntity($message);
        }, $messages->items);
    }
}
