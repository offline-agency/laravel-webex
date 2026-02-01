<?php

namespace Feature\Events\Http\Controllers;

use Offlineagency\LaravelWebex\Tests\TestCase;
use function Offlineagency\LaravelWebex\Tests\Feature\Http\Controllers\route;

class AuthControllerTest extends TestCase
{
    public function test_auth_route()
    {
        $this->get(route('auth'));

        $this->assertTrue(true);
    }
}
