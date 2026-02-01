<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\HistoricalAnalytics as HistoricalAnalyticsEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin HistoricalAnalytics', function () {
    it('lists historical analytics', function () {
        Http::fake([
            'https://webexapis.com/v1/historicalAnalytics*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'ha1',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->admin_historical_analytics()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(HistoricalAnalyticsEntity::class);
        expect($list[0]->id)->toEqual('ha1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/historicalAnalytics*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->admin_historical_analytics()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
