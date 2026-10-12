<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\SpaceClassification as SpaceClassificationEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class SpaceClassifications extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return SpaceClassificationEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId']);

        $response = $this->get('classifications', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new SpaceClassificationEntity($item), $items);
    }
}
