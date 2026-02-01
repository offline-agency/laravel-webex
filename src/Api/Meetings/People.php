<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\People as PeopleEntity;

class People extends AbstractApi
{
    public function listPeople(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'email', 'displayName', 'id', 'orgId', 'roles', 'callingData','locationId','max'
        ]);

        $response = $this->get('people', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $people = $response->data;

        return array_map(function ($peoples) {
            return new PeopleEntity($peoples);
        }, $people->items);
    }

    public function createPerson(
        string $emails,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData', 'minResponse', 'phoneNumbers', 'extension', 'locationId', 'displayName', 'firstName', 'lastName', 'avatar', 'orgId', 'roles', 'licenses', 'department', 'manager', 'managerId', 'title', 'addresses', 'siteUrls',
        ]);

        $response = $this->post('people/', array_merge([
            'emails' => $emails,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new PeopleEntity($response->data);
    }

    public function detailPerson(
        string $personId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData'
        ]);

        $response = $this->get('people/'.$personId, array_merge([
            'personId' => $personId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new PeopleEntity($response->data);
    }

    public function updatePerson(
        string $personId,
        string $displayName,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData', 'showAllTypes', 'minResponse', 'emails', 'phoneNumbers', 'extension', 'locationId', 'firstName', 'lastName', 'nickName', 'avatar', 'orgId', 'roles', 'licenses', 'department', 'manager', 'managerId', 'title', 'addresses','siteUrls', 'loginEnabled'
        ]);

        $response = $this->put('people/'.$personId, array_merge([
            'personId' => $personId,
            'displayName' => $displayName,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new PeopleEntity($response->data);
    }

    public function destroyPerson(
        string $personId
    ) {
        $response = $this->delete('meetingTranscripts/'.$personId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Person deleted';
    }

    public function detailOwn(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData'
        ]);

        $response = $this->get('people/', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new PeopleEntity($response->data);
    }
}
