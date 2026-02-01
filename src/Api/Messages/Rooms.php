<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Room as RoomEntity;

class Rooms extends AbstractApi
{
    /**
     * @return RoomEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'max', 'type', 'sortBy', 'teamId', 'orgId', 'meetingLinkEnabled', 'readOnly',
        ]);

        $response = $this->get('rooms', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($room) => new RoomEntity($room), $items);
    }

    /**
     * @return array{items: RoomEntity[], nextLink: string|null}|Error
     */
    public function listWithPagination(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'max', 'type', 'sortBy', 'teamId', 'orgId', 'meetingLinkEnabled', 'readOnly',
        ]);

        $response = $this->getWithLink('rooms', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return [
            'items' => array_map(fn ($room) => new RoomEntity($room), $items),
            'nextLink' => $response->nextLink ?? null,
        ];
    }

    /**
     * @return RoomEntity|Error
     */
    public function create(string $title, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'teamId', 'type', 'isLocked', 'description', 'classificationId', 'isAnnouncementOnly', 'isModerated', 'isReadOnly',
        ]);

        $response = $this->post('rooms', array_merge(['title' => $title], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoomEntity($response->data);
    }

    /**
     * @return RoomEntity|Error
     */
    public function detail(string $roomId)
    {
        $response = $this->get('rooms/'.$roomId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoomEntity($response->data);
    }

    /**
     * @return RoomEntity|Error
     */
    public function update(string $roomId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'title', 'isLocked', 'description', 'classificationId', 'isAnnouncementOnly', 'isModerated', 'isReadOnly',
        ]);

        $response = $this->put('rooms/'.$roomId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoomEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $roomId)
    {
        $response = $this->delete('rooms/'.$roomId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }

    /**
     * Get meeting details for a room.
     *
     * @return object|Error
     */
    public function meetingDetails(string $roomId)
    {
        $response = $this->get('rooms/'.$roomId.'/meetingInfo', []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }
}
