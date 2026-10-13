<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\SessionTypes as SessionTypesEntity;

class SessionTypes extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return SessionTypesEntity[]|Error
     */
    public function listSiteSessionTypes(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl',
        ]);

        $response = $this->get('admin/meeting/config/sessionTypes', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($sessionType) {
            return new SessionTypesEntity($sessionType);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return SessionTypesEntity[]|Error
     */
    public function listUserSessionType(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl', 'personId',
        ]);

        $response = $this->get('admin/meeting/userconfig/sessionTypes', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($sessionType) {
            return new SessionTypesEntity($sessionType);
        }, $items);
    }

    /**
     * @param  list<string>  $sessionTypeIds
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingsEntity|Error
     */
    public function update(
        string $siteUrl,
        array $sessionTypeIds,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'personId', 'email',
        ]);

        $response = $this->put('admin/meeting/userconfig/sessionTypes', array_merge([
            'siteUrl' => $siteUrl,
            'sessionTypeIds' => $sessionTypeIds,
        ], $additional_data));

        return $this->entity($response, MeetingsEntity::class);
    }
}
