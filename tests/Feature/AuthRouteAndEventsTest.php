<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\LaravelWebex;

it('dispatches AuthenticationRequested then SuccessfulAuthentication, and reads the token at request time', function () {
    $order = [];
    Event::listen(AuthenticationRequested::class, function () use (&$order) {
        $order[] = 'requested';
        config()->set('webex.bearer', 'injected-token');
    });
    Event::listen(SuccessfulAuthentication::class, function () use (&$order) {
        $order[] = 'configured';
    });
    Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response(['id' => 'm1'])]);

    $client = new LaravelWebex;
    config()->set('webex.bearer', 'current-token');
    $client->messages()->detail('m1');

    expect($order)->toBe(['requested', 'configured']);
    Http::assertSent(fn (ClientRequest $request) => $request->hasHeader('Authorization', 'Bearer current-token'));
});

it('registers the /auth route by default', function () {
    $response = app(Kernel::class)->handle(Request::create('/auth'));

    expect(Route::has('auth'))->toBeTrue()
        ->and($response->getStatusCode())->toBe(200)
        ->and($response->getContent())->toBe('OK');
});

it('fails a test that would reach the network', function () {
    Http::fake(['https://allowed.test/*' => Http::response('ok')]);

    expect(fn () => Http::get('https://webexapis.com/v1/people/me'))->toThrow(RuntimeException::class);
});
