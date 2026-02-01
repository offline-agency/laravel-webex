<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\HistoricalAnalytics as HistoricalAnalyticsEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class HistoricalAnalytics extends AbstractApi
{
    /**
     * List or get historical analytics (Admin).
     *
     * @return HistoricalAnalyticsEntity[]|HistoricalAnalyticsEntity|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'from', 'to']);

        $response = $this->get('historicalAnalytics', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new HistoricalAnalyticsEntity($item), $items);
    }
}
