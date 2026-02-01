<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\RoomTab as RoomTabEntity;

class RoomTabs extends AbstractApi
{
    /**
     * List room tabs for a room.
     *
     * @return RoomTabEntity[]|Error
     */
    public function list(string $roomId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('room/tabs', array_merge([
            'roomId' => $roomId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($tab) => new RoomTabEntity($tab), $items);
    }

    /**
     * Create a room tab.
     *
     * @return RoomTabEntity|Error
     */
    public function create(string $roomId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['contentUrl', 'displayName']);

        $response = $this->post('room/tabs', array_merge([
            'roomId' => $roomId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoomTabEntity($response->data);
    }

    /**
     * Get a single room tab by ID.
     *
     * @return RoomTabEntity|Error
     */
    public function detail(string $tabId)
    {
        $response = $this->get('room/tabs/'.$tabId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoomTabEntity($response->data);
    }

    /**
     * Update a room tab.
     *
     * @return RoomTabEntity|Error
     */
    public function update(string $tabId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['contentUrl', 'displayName']);

        $response = $this->put('room/tabs/'.$tabId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoomTabEntity($response->data);
    }

    /**
     * Delete a room tab.
     *
     * @return true|Error
     */
    public function destroy(string $tabId)
    {
        $response = $this->delete('room/tabs/'.$tabId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
