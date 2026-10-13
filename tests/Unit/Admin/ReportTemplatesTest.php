<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\ReportTemplate as ReportTemplateEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin ReportTemplates', function () {
    it('lists report templates', function () {
        Http::fake([
            'https://webexapis.com/v1/report/templates*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'tpl1',
                        'name' => 'Template One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_report_templates()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(ReportTemplateEntity::class);
        expect($list[0]->id)->toEqual('tpl1');
    });

});
