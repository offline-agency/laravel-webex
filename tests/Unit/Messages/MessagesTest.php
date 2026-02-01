<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Messages as MessageEntity;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Messages\MessagesFakeResponse;

dataset('messages_error_responses', [
    'unauthorized' => [401, 'fake_message'],
    'forbidden' => [403, 'Forbidden'],
]);

describe('Messages', function () {
    it('lists messages', function () {
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response(
                (new MessagesFakeResponse)->getMessagesFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $messages_list = $laravel_webex->messages()->list('fake_id');

        expect($messages_list)->toHaveCount(2);

        $single_message = null;
        foreach ($messages_list as $message) {
            expect($message)->toBeInstanceOf(MessageEntity::class);
            $single_message = $message;
        }

        expect($single_message->id)->toEqual('fake_id');
    });

    it('returns error on messages list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response(
                (new MessagesFakeResponse)->getMessagesFakeError(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->list('fake_id');

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
    });

    it('returns error with status and message from dataset', function (int $status, string $message) {
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response(
                json_encode((object) ['message' => $message, 'errors' => [], 'trackingId' => null]),
                $status
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->list('fake_id');

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual($message);
    })->with('messages_error_responses');

    it('returns error with safe defaults when response body is null or empty', function () {
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response('', 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->list('fake_id');

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Unknown error');
        expect($result->errors)->toEqual([]);
    });

    it('lists messages via container singleton', function () {
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response(
                (new MessagesFakeResponse)->getMessagesFakeList()
            ),
        ]);

        $laravel_webex = app('laravel-webex');
        expect($laravel_webex)->toBeInstanceOf(LaravelWebex::class);

        $messages_list = $laravel_webex->messages()->list('fake_id');

        expect($messages_list)->toHaveCount(2);
        expect($messages_list[0])->toBeInstanceOf(MessageEntity::class);
    });

    it('lists messages with pagination and returns next link', function () {
        $fake = new MessagesFakeResponse;
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response(
                $fake->getMessagesFakeList(),
                200,
                [
                    'Link' => '<https://webexapis.com/v1/messages?roomId=fake_id&max=2&before=abc>; rel="next"',
                ]
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->listWithPagination('fake_id', ['max' => 2]);

        expect($result)->toBeArray();
        expect($result)->toHaveKey('items');
        expect($result)->toHaveKey('nextLink');
        expect($result['items'])->toHaveCount(2);
        expect($result['nextLink'])->toEqual(
            'https://webexapis.com/v1/messages?roomId=fake_id&max=2&before=abc'
        );
    });

    it('creates message and sends correct request', function () {
        Http::fake([
            'https://webexapis.com/v1/messages' => Http::response(
                (new MessagesFakeResponse)->getMessagesFakeCreate()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $message = $laravel_webex->messages()->create('fake_room_id', 'Hello');

        expect($message)->toBeInstanceOf(MessageEntity::class);
        expect($message->text)->toEqual('new message');

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://webexapis.com/v1/messages'
                && $request['roomId'] === 'fake_room_id'
                && $request['text'] === 'Hello';
        });
    });

    it('gets message detail', function () {
        Http::fake([
            'https://webexapis.com/v1/messages/fake_msg_id' => Http::response(
                (new MessagesFakeResponse)->getMessagesFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $message = $laravel_webex->messages()->detail('fake_msg_id');

        expect($message)->toBeInstanceOf(MessageEntity::class);
        expect($message->id)->toEqual('fake_id');
    });

    it('destroys message', function () {
        Http::fake([
            'https://webexapis.com/v1/messages/fake_msg_id' => Http::response(null, 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->destroy('fake_msg_id');

        expect($result)->toBeTrue();
    });

    it('returns error on create message failure', function () {
        Http::fake([
            'https://webexapis.com/v1/messages' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->create('room1', 'Hello');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on message detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/messages/msg1' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->messages()->detail('msg1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
