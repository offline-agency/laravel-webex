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

it('builds the client with the token set in AuthenticationRequested, then dispatches SuccessfulAuthentication', function () {
    Event::listen(AuthenticationRequested::class, fn () => config()->set('webex.bearer', 'injected-token'));
    // the client is already built when SuccessfulAuthentication is dispatched: changing the token there has no effect
    Event::listen(SuccessfulAuthentication::class, fn () => config()->set('webex.bearer', 'too-late'));
    Http::fake(['https://webexapis.com/v1/messages/m1' => Http::response(['id' => 'm1'])]);

    (new LaravelWebex)->messages()->detail('m1');

    Http::assertSent(fn (ClientRequest $request) => $request->hasHeader('Authorization', 'Bearer injected-token'));
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
