<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\HybridConnector as HybridConnectorEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class HybridConnectors extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return HybridConnectorEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'clusterId']);

        $response = $this->get('hybrid/connectors', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new HybridConnectorEntity($item), $items);
    }

    /**
     * @return HybridConnectorEntity|Error
     */
    public function detail(string $connectorId)
    {
        $response = $this->get('hybrid/connectors/'.$connectorId, []);

        return $this->entity($response, HybridConnectorEntity::class);
    }
}
