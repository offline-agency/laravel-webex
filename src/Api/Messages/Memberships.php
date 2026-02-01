<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Membership as MembershipEntity;

class Memberships extends AbstractApi
{
    /**
     * @return MembershipEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'max', 'roomId', 'personId', 'personEmail', 'isModerator',
        ]);

        $response = $this->get('memberships', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($m) => new MembershipEntity($m), $items);
    }

    /**
     * @return MembershipEntity|Error
     */
    public function create(string $roomId, string $personEmail, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'personId', 'isModerator',
        ]);

        $response = $this->post('memberships', array_merge([
            'roomId' => $roomId,
            'personEmail' => $personEmail,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MembershipEntity($response->data);
    }

    /**
     * @return MembershipEntity|Error
     */
    public function detail(string $membershipId)
    {
        $response = $this->get('memberships/'.$membershipId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MembershipEntity($response->data);
    }

    /**
     * @return MembershipEntity|Error
     */
    public function update(string $membershipId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['isModerator']);

        $response = $this->put('memberships/'.$membershipId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MembershipEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $membershipId)
    {
        $response = $this->delete('memberships/'.$membershipId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
