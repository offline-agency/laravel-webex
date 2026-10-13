<?php

namespace Offlineagency\LaravelWebex\Tests\Feature\AuthRoute;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Offlineagency\LaravelWebex\Events\WebexClientConfigured;

final class EnabledAuthRouteTest extends AuthRouteTestCase
{
    protected function authRoute(): array
    {
        return ['enabled' => true];
    }

    public function test_the_enabled_route_answers_on_the_default_path_and_name(): void
    {
        Event::fake([WebexClientConfigured::class]);

        $this->assertTrue(Route::has('auth'));
        $this->get('/auth')->assertOk()->assertSeeText('OK');
        Event::assertDispatched(WebexClientConfigured::class);
    }
}
