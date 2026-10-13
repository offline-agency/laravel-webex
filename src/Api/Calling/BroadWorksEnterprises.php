<?php

namespace Offlineagency\LaravelWebex\Api\Calling;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Calling\BroadWorksEnterprise as BroadWorksEnterpriseEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class BroadWorksEnterprises extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return BroadWorksEnterpriseEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('broadworks/enterprises', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new BroadWorksEnterpriseEntity($item), $items);
    }
}
