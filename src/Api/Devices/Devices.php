<?php

namespace Offlineagency\LaravelWebex\Api\Devices;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Devices\Device as DeviceEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Devices extends AbstractApi
{
    /**
     * @return DeviceEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId', 'placeId', 'serial']);

        $response = $this->get('devices', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new DeviceEntity($item), $items);
    }

    /**
     * @return DeviceEntity|Error
     */
    public function detail(string $deviceId)
    {
        $response = $this->get('devices/'.$deviceId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new DeviceEntity($response->data);
    }

    /**
     * Create activation code for onboarding.
     *
     * @return object|Error
     */
    public function createActivationCode(array $body)
    {
        $response = $this->post('devices/activationCode', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }

    /**
     * @return true|Error
     */
    public function destroy(string $deviceId)
    {
        $response = $this->delete('devices/'.$deviceId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
