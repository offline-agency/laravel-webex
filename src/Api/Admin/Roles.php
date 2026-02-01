<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\Role as RoleEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Roles extends AbstractApi
{
    /**
     * @return RoleEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('roles', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new RoleEntity($item), $items);
    }

    /**
     * @return RoleEntity|Error
     */
    public function detail(string $roleId)
    {
        $response = $this->get('roles/'.$roleId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RoleEntity($response->data);
    }
}
