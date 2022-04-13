<?php

namespace Offlineagency\LaravelWebex\Tests\Feature\Events;

use Illuminate\Support\Facades\Event;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\TestCase;

class SuccessfulAuthenticationTest extends TestCase
{
    public function test_trigger_event()
    {
        Event::fake();

        new LaravelWebex();

        Event::assertDispatched(SuccessfulAuthentication::class);
    }
}
