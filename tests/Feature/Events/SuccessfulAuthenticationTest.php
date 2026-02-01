<?php

use Illuminate\Support\Facades\Event;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('SuccessfulAuthentication event', function () {
    it('triggers event when LaravelWebex is instantiated', function () {
        Event::fake();

        new LaravelWebex();

        Event::assertDispatched(SuccessfulAuthentication::class);
    });
});
