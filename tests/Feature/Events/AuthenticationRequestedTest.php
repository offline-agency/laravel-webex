<?php

use Illuminate\Support\Facades\Event;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('AuthenticationRequested event', function () {
    it('triggers event when LaravelWebex is instantiated', function () {
        Event::fake();

        new LaravelWebex;

        Event::assertDispatched(AuthenticationRequested::class);
    });
});
