<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\TeamMembership as TeamMembershipEntity;

class TeamMemberships extends AbstractApi
{
    /**
     * @return TeamMembershipEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['teamId', 'max', 'personId', 'personEmail']);

        $response = $this->get('team/memberships', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($m) => new TeamMembershipEntity($m), $items);
    }

    /**
     * @return TeamMembershipEntity|Error
     */
    public function create(string $teamId, string $personEmail, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['personId', 'isModerator']);

        $response = $this->post('team/memberships', array_merge([
            'teamId' => $teamId,
            'personEmail' => $personEmail,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TeamMembershipEntity($response->data);
    }

    /**
     * @return TeamMembershipEntity|Error
     */
    public function detail(string $membershipId)
    {
        $response = $this->get('team/memberships/'.$membershipId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TeamMembershipEntity($response->data);
    }

    /**
     * @return TeamMembershipEntity|Error
     */
    public function update(string $membershipId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['isModerator']);

        $response = $this->put('team/memberships/'.$membershipId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TeamMembershipEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $membershipId)
    {
        $response = $this->delete('team/memberships/'.$membershipId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
