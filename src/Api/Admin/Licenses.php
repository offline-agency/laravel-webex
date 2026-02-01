<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\License as LicenseEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Licenses extends AbstractApi
{
    /**
     * @return LicenseEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId']);

        $response = $this->get('licenses', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new LicenseEntity($item), $items);
    }

    /**
     * @return LicenseEntity|Error
     */
    public function detail(string $licenseId)
    {
        $response = $this->get('licenses/'.$licenseId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new LicenseEntity($response->data);
    }
}
