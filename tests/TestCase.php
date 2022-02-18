<?php

namespace Offlineagency\LaravelWebex\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('webex.base_url', 'https://webexapis.com/v1/');

        $app['config']->set('webex.access_token.url', 'access_token');
        $app['config']->set('webex.access_token.grant_type', 'authorization_code');

        $app['config']->set('webex.refresh_token.url', 'refresh_token');
        $app['config']->set('webex.refresh_token.grant_type', 'refresh_token');

        $app['config']->set('webex.client.id', '');
        $app['config']->set('webex.client.secret', '');
        $app['config']->set('webex.client.code', '');

        $app['config']->set('webex.redirect_uri', '');
    }
}
