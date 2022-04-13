<?php

namespace Offlineagency\LaravelWebex\Tests\Feature\Http\Controllers;

use Offlineagency\LaravelWebex\Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_auth_route()
    {
        $this->get(route('auth'));

        $this->assertTrue(true);
    }
}
