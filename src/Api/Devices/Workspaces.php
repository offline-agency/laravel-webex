<?php

namespace Offlineagency\LaravelWebex\Api\Devices;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Devices\Workspace as WorkspaceEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Workspaces extends AbstractApi
{
    /**
     * @return WorkspaceEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId', 'locationId']);

        $response = $this->get('workspaces', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new WorkspaceEntity($item), $items);
    }

    /**
     * @return WorkspaceEntity|Error
     */
    public function detail(string $workspaceId)
    {
        $response = $this->get('workspaces/'.$workspaceId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new WorkspaceEntity($response->data);
    }

    /**
     * @return WorkspaceEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('workspaces', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new WorkspaceEntity($response->data);
    }

    /**
     * @return WorkspaceEntity|Error
     */
    public function update(string $workspaceId, array $body)
    {
        $response = $this->put('workspaces/'.$workspaceId, $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new WorkspaceEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $workspaceId)
    {
        $response = $this->delete('workspaces/'.$workspaceId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
