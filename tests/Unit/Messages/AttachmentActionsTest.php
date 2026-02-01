<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\AttachmentAction as AttachmentActionEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('AttachmentActions', function () {
    it('creates attachment action', function () {
        Http::fake([
            'https://webexapis.com/v1/attachment/actions*' => Http::response(json_encode((object) [
                'id' => 'action1',
                'type' => 'submit',
                'messageId' => 'msg1',
                'personId' => 'p1',
                'personEmail' => 'user@example.com',
                'created' => '2024-01-01T00:00:00Z',
                'inputs' => (object) ['key' => 'value'],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $action = $laravel_webex->attachment_actions()->create('submit', 'msg1', ['key' => 'value']);

        expect($action)->toBeInstanceOf(AttachmentActionEntity::class);
        expect($action->id)->toEqual('action1');
        expect($action->type)->toEqual('submit');
        expect($action->messageId)->toEqual('msg1');
    });

    it('returns error on create failure', function () {
        Http::fake([
            'https://webexapis.com/v1/attachment/actions*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->attachment_actions()->create('submit', 'msg1', []);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets attachment action detail', function () {
        Http::fake([
            'https://webexapis.com/v1/attachment/actions/action1*' => Http::response(json_encode((object) [
                'id' => 'action1',
                'type' => 'submit',
                'messageId' => 'msg1',
                'personId' => 'p1',
                'created' => '2024-01-01T00:00:00Z',
                'inputs' => (object) [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $action = $laravel_webex->attachment_actions()->detail('action1');

        expect($action)->toBeInstanceOf(AttachmentActionEntity::class);
        expect($action->id)->toEqual('action1');
    });

    it('returns error on attachment action detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/attachment/actions/action1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->attachment_actions()->detail('action1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
