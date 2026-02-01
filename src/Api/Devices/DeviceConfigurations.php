<?php

namespace Offlineagency\LaravelWebex\Api\Devices;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Devices\DeviceConfiguration as DeviceConfigurationEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class DeviceConfigurations extends AbstractApi
{
    /**
     * List device configurations (optionally for a device).
     *
     * @return DeviceConfigurationEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'deviceId']);

        $response = $this->get('deviceConfigurations', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new DeviceConfigurationEntity($item), $items);
    }

    /**
     * Update device configurations (PATCH).
     *
     * @return object|Error
     */
    public function update(array $body)
    {
        $response = $this->patch('deviceConfigurations', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }
}
