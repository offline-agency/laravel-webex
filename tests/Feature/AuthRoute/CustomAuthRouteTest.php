<?php

namespace Offlineagency\LaravelWebex\Tests\Feature\AuthRoute;

use Illuminate\Support\Facades\Route;

final class CustomAuthRouteTest extends AuthRouteTestCase
{
    protected function authRoute(): array
    {
        return ['enabled' => true, 'path' => '/webex/auth', 'name' => 'webex.auth'];
    }

    public function test_the_package_route_path_and_name_are_configurable(): void
    {
        $this->assertTrue(Route::has('webex.auth'));
        $this->assertFalse(Route::has('auth'));
        $this->get('/webex/auth')->assertOk();
    }
}
