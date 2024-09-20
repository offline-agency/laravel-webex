<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\SessionTypes as SessionTypesEntity;

class SessionTypes extends AbstractApi
{
    public function listSiteSessionTypes(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl',
        ]);

        $response = $this->get('admin/meeting/config/sessionTypes', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $sessionTypes = $response->data;

        return array_map(function ($sessionType) {
            return new SessionTypesEntity($sessionType);
        }, $sessionTypes->items);
    }

    public function listUserSessionType(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl', 'personId',
        ]);

        $response = $this->get('admin/meeting/userconfig/sessionTypes', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $sessionTypes = $response->data;

        return array_map(function ($sessionType) {
            return new SessionTypesEntity($sessionType);
        }, $sessionTypes->items);
    }

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

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }
}
