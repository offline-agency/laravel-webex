<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroupMembership as ResourceGroupMembershipEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class ResourceGroupMemberships extends AbstractApi
{
    /**
     * @return ResourceGroupMembershipEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'resourceGroupId', 'personId']);

        $response = $this->get('resourceGroupMemberships', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new ResourceGroupMembershipEntity($item), $items);
    }

    /**
     * @return ResourceGroupMembershipEntity|Error
     */
    public function detail(string $membershipId)
    {
        $response = $this->get('resourceGroupMemberships/'.$membershipId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ResourceGroupMembershipEntity($response->data);
    }

    /**
     * @return ResourceGroupMembershipEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('resourceGroupMemberships', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ResourceGroupMembershipEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $membershipId)
    {
        $response = $this->delete('resourceGroupMemberships/'.$membershipId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
