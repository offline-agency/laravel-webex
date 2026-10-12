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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->tracking_codes()->listTrackingCodes();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on list tracking codes failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->listTrackingCodes();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets tracking code detail', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/tc1*' => Http::response(json_encode((object) [
                'id' => 'tc1',
                'name' => 'Code 1',
                'siteUrl' => 'https://example.webex.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->detailTrackingCode('tc1');

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on detail tracking code failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/tc1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->detailTrackingCode('tc1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates tracking code', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes*' => Http::response(json_encode((object) [
                'id' => 'tc2',
                'name' => 'New Code',
                'siteUrl' => 'https://example.webex.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->createTrackingCode(
            'New Code',
            'https://example.webex.com',
            [],
            [],
            'profile1',
            []
        );

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on create tracking code failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->createTrackingCode(
            'New Code',
            'https://example.webex.com',
            [],
            [],
            'profile1',
            []
        );

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updates tracking code', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/*' => Http::response(json_encode((object) [
                'id' => 'tc1',
                'name' => 'Updated Code',
                'siteUrl' => 'https://example.webex.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->updateTrackingCode(
            'Updated Code',
            'https://example.webex.com',
            [],
            [],
            'profile1',
            []
        );

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on update tracking code failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->updateTrackingCode(
            'Updated Code',
            'https://example.webex.com',
            [],
            [],
            'profile1',
            []
        );

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('destroys tracking code', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/tc1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->destroyTrackingCode('tc1', 'https://example.webex.com');

        expect($result)->toEqual('Tracking code deleted');
    });

    it('returns error on destroy tracking code failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/trackingCodes/tc1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->destroyTrackingCode('tc1', 'https://example.webex.com');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets user tracking codes', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/userconfig/trackingCodes*' => Http::response(json_encode((object) [
                'trackingCodes' => [],
                'siteUrl' => 'https://example.webex.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->detailUserTrackingCodes();

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on detail user tracking codes failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/userconfig/trackingCodes*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->detailUserTrackingCodes();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updates user tracking codes', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/userconfig/trackingCodes*' => Http::response(json_encode((object) [
                'trackingCodes' => [],
                'siteUrl' => 'https://example.webex.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->updateUserTrackingCodes('https://example.webex.com');

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('returns error on update user tracking codes failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/userconfig/trackingCodes*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->tracking_codes()->updateUserTrackingCodes('https://example.webex.com');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
