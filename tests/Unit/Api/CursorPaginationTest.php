<?php

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Offlineagency\LaravelWebex\Entities\Messages\Messages as MessageEntity;
use Offlineagency\LaravelWebex\Entities\Messages\Room;
use Offlineagency\LaravelWebex\Exceptions\WebexPaginationException;
use Offlineagency\LaravelWebex\Exceptions\WebexRequestException;
use Offlineagency\LaravelWebex\LaravelWebex;

beforeEach(function () {
    Sleep::fake();
    config()->set('webex.bearer', 'secret-token');
});

/**
 * @param  list<string>  $ids
 */
function messagesPage(array $ids, ?string $next = null): PromiseInterface
{
    return Http::response(['items' => array_map(fn (string $id) => ['id' => $id], $ids)], 200, $next === null ? [] : ['Link' => "<{$next}>; rel=\"next\""]);
}

it('iterates every page lazily following nextLink', function () {
    Http::fakeSequence('https://webexapis.com/v1/messages*')
        ->pushResponse(messagesPage(['m1', 'm2'], 'https://webexapis.com/v1/messages?roomId=r&max=2&beforeMessage=m2'))
        ->pushResponse(messagesPage(['m3']));

    $messages = (new LaravelWebex)->messages()->cursor('r', ['max' => 2]);
    Http::assertNothingSent();

    expect($messages->map(fn (MessageEntity $message) => $message->id)->all())->toBe(['m1', 'm2', 'm3']);
    Http::assertSent(fn (Request $request) => $request->url() === 'https://webexapis.com/v1/messages?roomId=r&max=2&beforeMessage=m2');
    Http::assertSentCount(2);
});

it('reads only the pages it needs', function () {
    Http::fakeSequence('https://webexapis.com/v1/rooms*')
        ->pushResponse(Http::response(['items' => [['id' => 'r1'], ['id' => 'r2']]], 200, ['Link' => '<https://webexapis.com/v1/rooms?cursor=2>; rel="next"']))
        ->pushResponse(Http::response(['items' => [['id' => 'r3']]]));

    $first = (new LaravelWebex)->rooms()->cursor()->take(2)->all();

    expect(array_map(fn (Room $room) => $room->id, $first))->toBe(['r1', 'r2']);
    Http::assertSentCount(1);
});

it('refuses to send the token to a nextLink on another host', function (string $next) {
    Http::fake(['https://webexapis.com/v1/messages*' => messagesPage(['m1'], $next), '*' => Http::response(['items' => []])]);

    expect(fn () => (new LaravelWebex)->messages()->cursor('r')->all())
        ->toThrow(WebexPaginationException::class, 'Refusing to follow a nextLink outside https://webexapis.com.');
    Http::assertSentCount(1);
})->with([
    'another host' => ['https://evil.example/v1/messages?page=2'],
    'a look-alike host' => ['https://webexapis.com.evil.example/v1/messages'],
    'plain http' => ['http://webexapis.com/v1/messages?page=2'],
]);

it('stops a repeated nextLink', function () {
    Http::fake(['*' => messagesPage(['m'], 'https://webexapis.com/v1/messages?roomId=r&page=same')]);

    expect(fn () => (new LaravelWebex)->messages()->cursor('r')->all())
        ->toThrow(WebexPaginationException::class, 'Webex returned the same nextLink twice.');
    Http::assertSentCount(2);
});

it('stops a traversal longer than the page limit', function () {
    $page = 0;
    Http::fake(['*' => function () use (&$page) {
        $page++;

        return messagesPage(["m{$page}"], "https://webexapis.com/v1/messages?roomId=r&page={$page}");
    }]);
    expect(fn () => (new LaravelWebex)->messages()->cursor('r', [], 3)->all())
        ->toThrow(WebexPaginationException::class, 'Stopped after 3 pages (maxPages).');
});

it('throws the Webex error of a failed page', function () {
    Http::fakeSequence('https://webexapis.com/v1/rooms*')
        ->pushResponse(Http::response(['items' => [['id' => 'r1']]], 200, ['Link' => '<https://webexapis.com/v1/rooms?cursor=2>; rel="next"']))
        ->push(['message' => 'Forbidden', 'trackingId' => 'T-9'], 403);

    $thrown = null;
    try {
        (new LaravelWebex)->rooms()->cursor()->all();
    } catch (WebexRequestException $e) {
        $thrown = $e;
    }

    expect($thrown)->toBeInstanceOf(WebexRequestException::class);
    assert($thrown instanceof WebexRequestException);
    expect($thrown->error->message)->toBe('Forbidden')
        ->and($thrown->error->trackingId)->toBe('T-9')
        ->and($thrown->error->status)->toBe(403)
        ->and($thrown->getMessage())->toBe('Webex request failed: Forbidden');
});
