<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\ReportTemplate as ReportTemplateEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin ReportTemplates', function () {
    it('lists report templates', function () {
        Http::fake([
            'https://webexapis.com/v1/reportTemplates*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'tpl1',
                        'name' => 'Template One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_report_templates()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(ReportTemplateEntity::class);
        expect($list[0]->id)->toEqual('tpl1');
    });

    it('gets report template detail', function () {
        Http::fake([
            'https://webexapis.com/v1/reportTemplates/tpl1*' => Http::response(json_encode((object) [
                'id' => 'tpl1',
                'name' => 'Template One',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $template = $laravel_webex->admin_report_templates()->detail('tpl1');

        expect($template)->toBeInstanceOf(ReportTemplateEntity::class);
        expect($template->id)->toEqual('tpl1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/reportTemplates*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_report_templates()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
