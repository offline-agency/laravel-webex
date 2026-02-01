<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\Report as ReportEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin Reports', function () {
    it('lists reports', function () {
        Http::fake([
            'https://webexapis.com/v1/reports*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'rep1',
                        'templateId' => 'tpl1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->admin_reports()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(ReportEntity::class);
        expect($list[0]->id)->toEqual('rep1');
    });

    it('gets report detail', function () {
        Http::fake([
            'https://webexapis.com/v1/reports/rep1*' => Http::response(json_encode((object) [
                'id' => 'rep1',
                'templateId' => 'tpl1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $report = $laravel_webex->admin_reports()->detail('rep1');

        expect($report)->toBeInstanceOf(ReportEntity::class);
        expect($report->id)->toEqual('rep1');
    });

    it('creates report', function () {
        Http::fake([
            'https://webexapis.com/v1/reports' => Http::response(json_encode((object) [
                'id' => 'rep2',
                'templateId' => 'tpl1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $report = $laravel_webex->admin_reports()->create(['templateId' => 'tpl1']);

        expect($report)->toBeInstanceOf(ReportEntity::class);
        expect($report->id)->toEqual('rep2');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/reports*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->admin_reports()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
