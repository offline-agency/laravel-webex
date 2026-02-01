<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\Organization as OrganizationEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Organizations extends AbstractApi
{
    /**
     * @return OrganizationEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('organizations', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new OrganizationEntity($item), $items);
    }

    /**
     * @return OrganizationEntity|Error
     */
    public function detail(string $orgId)
    {
        $response = $this->get('organizations/'.$orgId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new OrganizationEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $orgId)
    {
        $response = $this->delete('organizations/'.$orgId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
