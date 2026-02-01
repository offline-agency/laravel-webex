<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\TrackingCodes as TrackingCodesEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('TrackingCodes', function () {
    it('lists tracking codes', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'tc1', 'name' => 'Code 1', 'siteUrl' => 'https://example.webex.com'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->tracking_codes()->listTrackingCodes();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on list tracking codes failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->tracking_codes()->listTrackingCodes();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets tracking code detail', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/tc1*' => Http::response(json_encode((object) [
                'id' => 'tc1',
                'name' => 'Code 1',
                'siteUrl' => 'https://example.webex.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->tracking_codes()->detailTrackingCode('tc1');

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });
});
