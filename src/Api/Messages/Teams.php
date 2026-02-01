<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Team as TeamEntity;

class Teams extends AbstractApi
{
    /**
     * @return TeamEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('teams', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($team) => new TeamEntity($team), $items);
    }

    /**
     * @return TeamEntity|Error
     */
    public function create(string $name, ?array $additional_data = [])
    {
        $response = $this->post('teams', array_merge(['name' => $name], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TeamEntity($response->data);
    }

    /**
     * @return TeamEntity|Error
     */
    public function detail(string $teamId)
    {
        $response = $this->get('teams/'.$teamId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TeamEntity($response->data);
    }

    /**
     * @return TeamEntity|Error
     */
    public function update(string $teamId, string $name)
    {
        $response = $this->put('teams/'.$teamId, ['name' => $name]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TeamEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $teamId)
    {
        $response = $this->delete('teams/'.$teamId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
