<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\Report as ReportEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class Reports extends AbstractApi
{
    /**
     * @return ReportEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'templateId']);

        $response = $this->get('reports', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new ReportEntity($item), $items);
    }

    /**
     * @return ReportEntity|Error
     */
    public function detail(string $reportId)
    {
        $response = $this->get('reports/'.$reportId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ReportEntity($response->data);
    }

    /**
     * Create (run) a report.
     *
     * @return ReportEntity|Error
     */
    public function create(array $body)
    {
        $response = $this->post('reports', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ReportEntity($response->data);
    }
}
