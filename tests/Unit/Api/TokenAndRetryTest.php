<?php

use Carbon\CarbonInterval;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Offlineagency\LaravelWebex\Contracts\TokenResolver;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Messages as MessageEntity;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fixtures\SequenceTokenResolver;

beforeEach(function () {
    Sleep::fake();
    config()->set('webex.bearer', 'config-token');
});

/**
 * @return list<string>
 */
function sentAuthorizations(): array
{
    $authorizations = [];
    foreach (Http::recorded() as [$request]) {
        $header = $request->header('Authorization')[0] ?? '';
        $authorizations[] = is_string($header) ? $header : '';
    }

    return $authorizations;
}

describe('token resolution', function () {
    it('reads the token for every request, so a long-lived client sees a refreshed token', function () {
        Http::fake(['*' => Http::response(['id' => 'm1'])]);
        $client = new LaravelWebex;

        $client->messages()->detail('m1');
        config()->set('webex.bearer', 'refreshed-token');
        $client->messages()->detail('m1');

        expect(sentAuthorizations())->toBe(['Bearer config-token', 'Bearer refreshed-token']);
    });

    it('asks the configured token resolver on every request', function () {
        config()->set('webex.token_resolver', SequenceTokenResolver::class);
        SequenceTokenResolver::$tokens = ['first', 'second'];
        Http::fake(['*' => Http::response(['id' => 'm1'])]);
        $client = new LaravelWebex;

        $client->messages()->detail('m1');
        $client->messages()->detail('m1');

        expect(sentAuthorizations())->toBe(['Bearer first', 'Bearer second']);
    });

    it('binds a client to one account token without changing the shared one', function () {
        Http::fake(['*' => Http::response(['id' => 'm1'])]);

        (new LaravelWebex)->forToken('account-b')->messages()->detail('m1');
        (new LaravelWebex)->messages()->detail('m1');

        expect(sentAuthorizations())->toBe(['Bearer account-b', 'Bearer config-token']);
    });

    it('rejects a token resolver that does not implement the contract', function () {
        config()->set('webex.token_resolver', stdClass::class);
        Http::fake(['*' => Http::response(['id' => 'm1'])]);

        expect(fn () => (new LaravelWebex)->messages()->detail('m1'))
            ->toThrow(InvalidArgumentException::class, 'webex.token_resolver must name a class implementing '.TokenResolver::class);
    });
});

describe('rate limiting and transient failures', function () {
    it('retries an idempotent request after 429, waiting for Retry-After', function () {
        Http::fakeSequence('https://webexapis.com/v1/messages/m1')
            ->push(['message' => 'Too many requests'], 429, ['Retry-After' => '3'])
            ->push(['id' => 'm1'], 200);

        $result = (new LaravelWebex)->messages()->detail('m1');

        expect($result)->toBeInstanceOf(MessageEntity::class);
        Http::assertSentCount(2);
        Sleep::assertSlept(fn (CarbonInterval $duration) => (int) $duration->totalSeconds === 3, 1);
    });

    it('caps the wait and gives up after the configured attempts, returning the 429 as an Error', function () {
        config()->set('webex.retry', ['times' => 3, 'max_delay_seconds' => 5]);
        Http::fake(['*' => Http::response(['message' => 'Too many requests', 'trackingId' => 'T'], 429, ['Retry-After' => '120'])]);

        $result = (new LaravelWebex)->messages()->detail('m1');

        assert($result instanceof Error);
        expect($result->status)->toBe(429)
            ->and($result->retryAfter)->toBe(120)
            ->and($result->message)->toBe('Too many requests');
        Http::assertSentCount(3);
        Sleep::assertSlept(fn (CarbonInterval $duration) => (int) $duration->totalSeconds === 5, 2);
    });

    it('never retries a non-idempotent POST or PATCH', function () {
        Http::fake(['*' => Http::response(['message' => 'Too many requests'], 429, ['Retry-After' => '1'])]);

        $result = (new LaravelWebex)->messages()->create('room', 'hello');

        assert($result instanceof Error);
        expect($result->status)->toBe(429);
        Http::assertSentCount(1);
        Sleep::assertNeverSlept();
    });

    it('retries a GET after a transport failure and a 503, not after another error', function () {
        Http::fakeSequence('https://webexapis.com/v1/rooms/r1')
            ->pushFailedConnection()
            ->push('', 503)
            ->push(['id' => 'r1'], 200);
        Http::fakeSequence('https://webexapis.com/v1/rooms/r2')
            ->push(['message' => 'Not found'], 404);
        $client = new LaravelWebex;

        expect($client->rooms()->detail('r1'))->not->toBeInstanceOf(Error::class);
        $notFound = $client->rooms()->detail('r2');
        assert($notFound instanceof Error);
        expect($notFound->status)->toBe(404);
        Http::assertSentCount(4);
    });

    it('reads Retry-After given as an HTTP date, and ignores an invalid one', function (string $header, ?int $expected) {
        config()->set('webex.retry', ['times' => 1]);
        Http::fake(['*' => Http::response(['message' => 'Too many requests'], 429, ['Retry-After' => $header])]);

        $result = (new LaravelWebex)->messages()->detail('m1');

        assert($result instanceof Error);
        expect($result->retryAfter)->toBe($expected);
    })->with([
        'a date in the past' => ['Wed, 21 Oct 2015 07:28:00 GMT', 0],
        'an invalid value' => ['soon', null],
    ]);

    it('can disable retries', function () {
        config()->set('webex.retry', ['times' => 1]);
        Http::fake(['*' => Http::response(['message' => 'Too many requests'], 429)]);

        (new LaravelWebex)->messages()->detail('m1');

        Http::assertSentCount(1);
    });

    it('reports a transport failure that persists after the retries', function () {
        Http::fake(fn () => throw new ConnectionException('cURL error 7: connection refused'));

        $result = (new LaravelWebex)->rooms()->detail('r1');

        assert($result instanceof Error);
        expect($result->message)->toBe('Connection to Webex failed: cURL error 7: connection refused')
            ->and($result->status)->toBeNull();
    });
});
