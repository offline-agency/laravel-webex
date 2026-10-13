<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Messages as MessageEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

beforeEach(function () {
    config()->set('webex.bearer', 'secret-bearer-token');
});

describe('HTTP response contract', function () {
    it('accepts any 2xx status as success', function (int $status) {
        Http::fake(['https://webexapis.com/v1/messages' => Http::response(['id' => 'created', 'text' => 'hello'], $status)]);

        $message = (new LaravelWebex)->messages()->create('room', 'hello');

        expect($message)->toBeInstanceOf(MessageEntity::class);
        assert($message instanceof MessageEntity);
        expect($message->id)->toBe('created');
    })->with(['200 OK' => [200], '201 Created' => [201], '202 Accepted' => [202]]);

    it('returns true for an empty 204 on delete', function () {
        Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response('', 204)]);

        expect((new LaravelWebex)->messages()->destroy('m1'))->toBeTrue();
    });

    it('reports malformed JSON instead of an empty list', function () {
        Http::fake(['https://webexapis.com/v1/messages*' => Http::response('{"items": [', 200)]);

        $result = (new LaravelWebex)->messages()->list('room');

        expect($result)->toBeInstanceOf(Error::class);
        assert($result instanceof Error);
        expect($result->message)->toBe('Webex returned a malformed JSON response (HTTP 200).');
    });

    it('does not build an entity from a body that holds none', function (string $body, string $message) {
        Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response($body, 200)]);

        $result = (new LaravelWebex)->messages()->detail('m1');

        expect($result)->toBeInstanceOf(Error::class);
        assert($result instanceof Error);
        expect($result->message)->toBe($message);
    })->with([
        'empty body' => ['', 'Webex returned no document (HTTP 200).'],
        'JSON null' => ['null', 'Webex returned no document (HTTP 200).'],
        'JSON scalar' => ['"text"', 'Webex returned an unexpected JSON value (HTTP 200).'],
    ]);

    it('keeps the Webex error of a failed request', function () {
        Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response(['message' => 'Not found', 'errors' => [['description' => 'x']], 'trackingId' => 'T-1'], 404)]);

        $result = (new LaravelWebex)->messages()->detail('m1');

        assert($result instanceof Error);
        expect($result->message)->toBe('Not found')
            ->and($result->trackingId)->toBe('T-1')
            ->and($result->errors)->toEqual([(object) ['description' => 'x']]);
    });

    it('reports a failed request whose body is not a Webex error without echoing the body', function () {
        Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response('<html>proxy error secret-bearer-token</html>', 502)]);

        $result = (new LaravelWebex)->messages()->detail('m1');

        assert($result instanceof Error);
        expect($result->message)->toBe('Webex request failed (HTTP 502).')
            ->and(json_encode($result))->not->toContain('secret-bearer-token');
    });

    it('reports a transport failure without the bearer token', function () {
        Http::fake(fn () => throw new ConnectionException('cURL error 28: timed out after 30000 ms'));

        $result = (new LaravelWebex)->messages()->detail('m1');

        assert($result instanceof Error);
        expect($result->message)->toBe('Connection to Webex failed: cURL error 28: timed out after 30000 ms')
            ->and(json_encode($result))->not->toContain('secret-bearer-token');
    });

    it('does not disguise a programming error as a Webex error', function () {
        Http::fake(fn () => throw new TypeError('a bug in the caller'));

        expect(fn () => (new LaravelWebex)->messages()->detail('m1'))->toThrow(TypeError::class, 'a bug in the caller');
    });

    it('ignores a field whose type does not match a typed entity property instead of crashing', function () {
        Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response(['message' => ['not', 'a', 'string'], 'trackingId' => 7], 400)]);

        $result = (new LaravelWebex)->messages()->detail('m1');

        assert($result instanceof Error);
        expect($result->message)->toBe('Unknown error')
            ->and($result->trackingId)->toBeNull();
    });

    it('sends the bearer token, the timeout and only the allowed options', function () {
        config()->set('webex.timeout', 12);
        Http::fake(['https://webexapis.com/v1/messages*' => Http::response(['items' => []], 200)]);

        (new LaravelWebex)->messages()->list('room', ['max' => 5, 'injected' => 'dropped']);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Authorization', 'Bearer secret-bearer-token')
                && $request->url() === 'https://webexapis.com/v1/messages?roomId=room&max=5';
        });
    });

    it('accepts null options', function () {
        Http::fake(['https://webexapis.com/v1/messages*' => Http::response(['items' => []], 200)]);

        expect((new LaravelWebex)->messages()->list('room', null))->toBe([]);
    });

    it('builds an empty entity when given null explicitly', function () {
        $error = new Error(null);

        expect($error->message)->toBe('Unknown error')
            ->and($error->errors)->toBe([]);
    });
});
