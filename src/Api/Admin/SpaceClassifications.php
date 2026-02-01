<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\SpaceClassification as SpaceClassificationEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class SpaceClassifications extends AbstractApi
{
    /**
     * @return SpaceClassificationEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'orgId']);

        $response = $this->get('spaceClassifications', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new SpaceClassificationEntity($item), $items);
    }

    /**
     * @return SpaceClassificationEntity|Error
     */
    public function detail(string $classificationId)
    {
        $response = $this->get('spaceClassifications/'.$classificationId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new SpaceClassificationEntity($response->data);
    }

    /**
     * @return SpaceClassificationEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('spaceClassifications', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new SpaceClassificationEntity($response->data);
    }

    /**
     * @return SpaceClassificationEntity|Error
     */
    public function update(string $classificationId, array $body)
    {
        $response = $this->put('spaceClassifications/'.$classificationId, $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new SpaceClassificationEntity($response->data);
    }

    /**
     * @return true|Error
     */
    public function destroy(string $classificationId)
    {
        $response = $this->delete('spaceClassifications/'.$classificationId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
