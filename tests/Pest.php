<?php

use Offlineagency\LaravelWebex\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "TestCase". Of course, you may need to change
| it to the name of a fully qualified class that you use for your tests.
|
*/

uses(TestCase::class)->in('Feature', 'Unit');
