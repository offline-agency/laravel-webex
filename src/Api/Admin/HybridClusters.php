<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\HybridCluster as HybridClusterEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class HybridClusters extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return HybridClusterEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('hybrid/clusters', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new HybridClusterEntity($item), $items);
    }

    /**
     * @return HybridClusterEntity|Error
     */
    public function detail(string $clusterId)
    {
        $response = $this->get('hybrid/clusters/'.$clusterId, []);

        return $this->entity($response, HybridClusterEntity::class);
    }
}
