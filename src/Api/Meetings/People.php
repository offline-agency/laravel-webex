<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\People as PeopleEntity;

class People extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return PeopleEntity[]|Error
     */
    public function listPeople(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'email', 'displayName', 'id', 'orgId', 'roles', 'callingData', 'locationId', 'max',
        ]);

        $response = $this->get('people', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($person) {
            return new PeopleEntity($person);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return PeopleEntity|Error
     */
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

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return PeopleEntity|Error
     */
    public function detailPerson(
        string $personId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData',
        ]);

        $response = $this->get('people/'.$personId, array_merge([
            'personId' => $personId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new PeopleEntity($response->data);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return PeopleEntity|Error
     */
    public function updatePerson(
        string $personId,
        string $displayName,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData', 'showAllTypes', 'minResponse', 'emails', 'phoneNumbers', 'extension', 'locationId', 'firstName', 'lastName', 'nickName', 'avatar', 'orgId', 'roles', 'licenses', 'department', 'manager', 'managerId', 'title', 'addresses', 'siteUrls', 'loginEnabled',
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

    /**
     * @return string|Error
     */
    public function destroyPerson(
        string $personId
    ) {
        $response = $this->delete('people/'.$personId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Person deleted';
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return PeopleEntity|Error
     */
    public function detailOwn(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'callingData',
        ]);

        $response = $this->get('people/', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new PeopleEntity($response->data);
    }
}
