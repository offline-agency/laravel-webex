<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\ReportTemplate as ReportTemplateEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class ReportTemplates extends AbstractApi
{
    /**
     * @return ReportTemplateEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max']);

        $response = $this->get('reportTemplates', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new ReportTemplateEntity($item), $items);
    }

    /**
     * @return ReportTemplateEntity|Error
     */
    public function detail(string $templateId)
    {
        $response = $this->get('reportTemplates/'.$templateId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new ReportTemplateEntity($response->data);
    }
}
