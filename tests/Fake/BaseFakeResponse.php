<?php

namespace Offlineagency\LaravelWebex\Tests\Fake;

use Illuminate\Support\Arr;

class BaseFakeResponse
{
    public function value(
        array  $params,
        string $key,
               $default
    )
    {
        return Arr::has($params, $key)
            ? Arr::get($params, $key)
            : $default;
    }
}
