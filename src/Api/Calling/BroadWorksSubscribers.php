<?php

namespace Offlineagency\LaravelWebex\Api\Calling;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Calling\BroadWorksSubscriber as BroadWorksSubscriberEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class BroadWorksSubscribers extends AbstractApi
{
    /**
     * @return BroadWorksSubscriberEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'enterpriseId']);

        $response = $this->get('broadworksSubscribers', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new BroadWorksSubscriberEntity($item), $items);
    }

    /**
     * @return BroadWorksSubscriberEntity|Error
     */
    public function detail(string $subscriberId)
    {
        $response = $this->get('broadworksSubscribers/'.$subscriberId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new BroadWorksSubscriberEntity($response->data);
    }
}
