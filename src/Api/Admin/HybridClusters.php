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

        $response = $this->get('hybridClusters', $additional_data);

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
        $response = $this->get('hybridClusters/'.$clusterId, []);

        return $this->entity($response, HybridClusterEntity::class);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return HybridClusterEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('hybridClusters', $body);

        return $this->entity($response, HybridClusterEntity::class);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return HybridClusterEntity|Error
     */
    public function update(string $clusterId, array $body)
    {
        $response = $this->put('hybridClusters/'.$clusterId, $body);

        return $this->entity($response, HybridClusterEntity::class);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $clusterId)
    {
        $response = $this->delete('hybridClusters/'.$clusterId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
