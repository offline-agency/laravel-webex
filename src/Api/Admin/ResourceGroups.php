<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroup as ResourceGroupEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class ResourceGroups extends AbstractApi
{
    /**
     * @return ResourceGroupEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId']);

        $response = $this->get('resourceGroups', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new ResourceGroupEntity($item), $items);
    }

    /**
     * @return ResourceGroupEntity|Error
     */
    public function detail(string $resourceGroupId)
    {
        $response = $this->get('resourceGroups/'.$resourceGroupId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ResourceGroupEntity($response->data);
    }

    /**
     * @return ResourceGroupEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('resourceGroups', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ResourceGroupEntity($response->data);
    }

    /**
     * @return ResourceGroupEntity|Error
     */
    public function update(string $resourceGroupId, array $body)
    {
        $response = $this->put('resourceGroups/'.$resourceGroupId, $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ResourceGroupEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $resourceGroupId)
    {
        $response = $this->delete('resourceGroups/'.$resourceGroupId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
