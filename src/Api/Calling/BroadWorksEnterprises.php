<?php

namespace Offlineagency\LaravelWebex\Api\Calling;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Calling\BroadWorksEnterprise as BroadWorksEnterpriseEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class BroadWorksEnterprises extends AbstractApi
{
    /**
     * @return BroadWorksEnterpriseEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('broadworksEnterprises', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new BroadWorksEnterpriseEntity($item), $items);
    }

    /**
     * @return BroadWorksEnterpriseEntity|Error
     */
    public function detail(string $enterpriseId)
    {
        $response = $this->get('broadworksEnterprises/'.$enterpriseId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new BroadWorksEnterpriseEntity($response->data);
    }
}
