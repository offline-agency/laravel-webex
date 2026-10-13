<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroup as ResourceGroupEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class ResourceGroups extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
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

        return $this->entity($response, ResourceGroupEntity::class);
    }
}
