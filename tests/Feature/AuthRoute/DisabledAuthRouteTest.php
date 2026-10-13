<?php

namespace Offlineagency\LaravelWebex\Tests\Feature\AuthRoute;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

final class DisabledAuthRouteTest extends AuthRouteTestCase
{
    protected function authRoute(): array
    {
        return ['enabled' => false];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        // the application's own route named "auth", registered before the package boots, is not overridden
        $router = $app->make(Router::class);
        $router->get('/auth', fn () => 'application auth')->name('auth');
    }

    public function test_the_package_route_can_be_disabled_to_avoid_a_collision(): void
    {
        $this->get('/auth')->assertOk()->assertSeeText('application auth');
        $this->assertSame(1, collect(Route::getRoutes()->getRoutes())->filter(fn ($route) => $route->uri() === 'auth')->count());
    }
}
