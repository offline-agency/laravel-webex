<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\Webhooks as WebhookEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Webhooks', function () {
    it('lists webhooks', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'w1', 'name' => 'Test', 'targetUrl' => 'https://example.com'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->webhooks()->listWebhooks();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WebhookEntity::class);
        expect($list[0]->id)->toEqual('w1');
    });

    it('creates webhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks*' => Http::response(json_encode((object) [
                'id' => 'w2',
                'name' => 'New Webhook',
                'targetUrl' => 'https://example.com/callback',
                'resource' => 'meetings',
                'event' => 'created',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->createWebhook('New Webhook', 'https://example.com/callback', 'meetings', 'created');
        assert($webhook instanceof WebhookEntity);

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->id)->toEqual('w2');
        expect($webhook->name)->toEqual('New Webhook');
    });

    it('gets webhook detail', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'id' => 'w1',
                'name' => 'Test',
                'targetUrl' => 'https://example.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->detailWebhook('w1');
        assert($webhook instanceof WebhookEntity);

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->id)->toEqual('w1');
    });

    it('updates webhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'id' => 'w1',
                'name' => 'Updated Webhook',
                'targetUrl' => 'https://example.com/updated',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->updateWebhook('w1', 'Updated Webhook', 'https://example.com/updated');
        assert($webhook instanceof WebhookEntity);

        expect($webhook)->toBeInstanceOf(WebhookEntity::class);
        expect($webhook->name)->toEqual('Updated Webhook');
    });

    it('updateTrackingCode is alias for updateWebhook', function () {
        Http::fake([
            'https://webexapis.com/v1/webhooks/w1*' => Http::response(json_encode((object) [
                'id' => 'w1',
                'name' => 'Alias Updated',
                'targetUrl' => 'https://example.com/alias',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $webhook = $laravel_webex->webhooks()->updateTrackingCode('w1', 'Alias Updated', 'https://example.com/alias');
        assert($webhook instanceof WebhookEntity);

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

});
