<?php

namespace Offlineagency\LaravelWebex\Api\Devices;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Devices\WorkspaceMetrics as WorkspaceMetricsEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class WorkspaceMetrics extends AbstractApi
{
    /**
     * List workspace metrics.
     *
     * @return WorkspaceMetricsEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId', 'locationId']);

        $response = $this->get('workspaceMetrics', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new WorkspaceMetricsEntity($item), $items);
    }
}
