<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\Location as LocationEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Locations extends AbstractApi
{
    /**
     * @return LocationEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId']);

        $response = $this->get('locations', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new LocationEntity($item), $items);
    }

    /**
     * @return LocationEntity|Error
     */
    public function detail(string $locationId)
    {
        $response = $this->get('locations/'.$locationId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new LocationEntity($response->data);
    }

    /**
     * @return LocationEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('locations', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new LocationEntity($response->data);
    }

    /**
     * @return LocationEntity|Error
     */
    public function update(string $locationId, array $body)
    {
        $response = $this->put('locations/'.$locationId, $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new LocationEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $locationId)
    {
        $response = $this->delete('locations/'.$locationId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
