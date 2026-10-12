<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\BroadWorksSubscriber as BroadWorksSubscriberEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Calling BroadWorksSubscribers', function () {
    it('lists broadworks subscribers', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksSubscribers*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'sub1',
                        'enterpriseId' => 'ent1',
                        'name' => 'Subscriber One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->broadworks_subscribers()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(BroadWorksSubscriberEntity::class);
        expect($list[0]->id)->toEqual('sub1');
    });

    it('gets broadworks subscriber detail', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksSubscribers/sub1*' => Http::response(json_encode((object) [
                'id' => 'sub1',
                'enterpriseId' => 'ent1',
                'name' => 'Subscriber One',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $subscriber = $laravel_webex->broadworks_subscribers()->detail('sub1');
        assert($subscriber instanceof BroadWorksSubscriberEntity);

        expect($subscriber)->toBeInstanceOf(BroadWorksSubscriberEntity::class);
        expect($subscriber->id)->toEqual('sub1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksSubscribers*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->broadworks_subscribers()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksSubscribers/sub1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->broadworks_subscribers()->detail('sub1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });
});
