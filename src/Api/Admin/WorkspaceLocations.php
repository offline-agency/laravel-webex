<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\WorkspaceLocation as WorkspaceLocationEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class WorkspaceLocations extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return WorkspaceLocationEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId']);

        $response = $this->get('workspaceLocations', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new WorkspaceLocationEntity($item), $items);
    }

    /**
     * @return WorkspaceLocationEntity|Error
     */
    public function detail(string $locationId)
    {
        $response = $this->get('workspaceLocations/'.$locationId, []);

        return $this->entity($response, WorkspaceLocationEntity::class);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return WorkspaceLocationEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('workspaceLocations', $body);

        return $this->entity($response, WorkspaceLocationEntity::class);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return WorkspaceLocationEntity|Error
     */
    public function update(string $locationId, array $body)
    {
        $response = $this->put('workspaceLocations/'.$locationId, $body);

        return $this->entity($response, WorkspaceLocationEntity::class);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $locationId)
    {
        $response = $this->delete('workspaceLocations/'.$locationId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
