<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Webhooks as WebhookEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Webhooks', function () {
    it('lists webhooks', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'w1', 'name' => 'Test', 'targetUrl' => 'https://example.com'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->webhooks()->listWebhooks();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WebhookEntity::class);
        expect($list[0]->id)->toEqual('w1');
    });

    it('returns error on webhooks list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->webhooks()->listWebhooks();

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Unauthorized');
    });

    it('creates webhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks*' => Http::response(json_encode((object) [
                'id' => 'w2',
                'name' => 'New Webhook',
                'targetUrl' => 'https://example.com/callback',
                'resource' => 'meetings',
                'event' => 'created',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->createWebhook('New Webhook', 'https://example.com/callback', ['meetings'], ['created']);

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->id)->toEqual('w2');
        expect($webhook->name)->toEqual('New Webhook');
    });

    it('returns error on create webhook failure', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->webhooks()->createWebhook('New Webhook', 'https://example.com/callback', ['meetings'], ['created']);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets webhook detail', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'id' => 'w1',
                'name' => 'Test',
                'targetUrl' => 'https://example.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->detailWebhook('w1');

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->id)->toEqual('w1');
    });

    it('returns error on webhook detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->webhooks()->detailWebhook('w1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updates webhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'id' => 'w1',
                'name' => 'Updated Webhook',
                'targetUrl' => 'https://example.com/updated',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->updateWebhook('w1', 'Updated Webhook', 'https://example.com/updated');

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->name)->toEqual('Updated Webhook');
    });

    it('returns error on update webhook failure', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->webhooks()->updateWebhook('w1', 'Updated Webhook', 'https://example.com/updated');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updateTrackingCode is alias for updateWebhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'id' => 'w1',
                'name' => 'Alias Updated',
                'targetUrl' => 'https://example.com/alias',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->updateTrackingCode('w1', 'Alias Updated', 'https://example.com/alias');

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->name)->toEqual('Alias Updated');
    });

    it('destroys webhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->webhooks()->destroyWebhook('w1');

        expect($result)->toEqual('Webhook deleted');
    });

    it('returns error on destroy webhook failure', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->webhooks()->destroyWebhook('w1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
