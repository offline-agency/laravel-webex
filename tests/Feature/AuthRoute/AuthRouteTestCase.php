<?php

namespace Offlineagency\LaravelWebex\Tests\Feature\AuthRoute;

use Illuminate\Foundation\Application;
use Offlineagency\LaravelWebex\Tests\TestCase;

/**
 * The route is registered while the provider boots: these tests configure it before the application boots.
 */
abstract class AuthRouteTestCase extends TestCase
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
