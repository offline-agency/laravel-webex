<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Event as EventEntity;

class Events extends AbstractApi
{
    /**
     * List events (Compliance API; requires spark-compliance:events_read scope).
     *
     * @return EventEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'max', 'resource', 'type', 'actorId', 'from', 'to',
        ]);

        $response = $this->get('events', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($event) => new EventEntity($event), $items);
    }
}
