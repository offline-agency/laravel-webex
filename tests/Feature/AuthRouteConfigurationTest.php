<?php

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Offlineagency\LaravelWebex\Tests\TestCase;

/**
 * The route is registered while the provider boots: these tests configure it before the application boots.
 */
abstract class AuthRouteConfigurationTestCase extends TestCase
{
    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('webex.auth_route', $this->authRoute());
    }

    /**
     * @return array<string, mixed>
     */
    abstract protected function authRoute(): array;
}

final class DisabledAuthRouteTest extends AuthRouteConfigurationTestCase
{
    protected function authRoute(): array
    {
        return ['enabled' => false];
    }

    /**
     * @param  Router  $router
     */
    protected function defineRoutes($router): void
    {
        // the application's own route named "auth" is not overridden
        $router->get('/auth', fn () => 'application auth')->name('auth');
    }

    public function test_the_package_route_can_be_disabled_to_avoid_a_collision(): void
    {
        $this->get('/auth')->assertOk()->assertSeeText('application auth');
        $this->assertSame(1, collect(Route::getRoutes()->getRoutes())->filter(fn ($route) => $route->uri() === 'auth')->count());
    }
}

final class CustomAuthRouteTest extends AuthRouteConfigurationTestCase
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
