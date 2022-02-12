<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class Meetings extends BaseEntity
{
    public function meetings(
        array  $params = [],
        string $fieldset = 'basic'
    )
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer fake_bearer'
        ])->get(
            config('laravel-webex.base_url') . 'meetings',
            $params
        );

        return $this->formatResponse(
            $response,
            $fieldset,
            'items'
        );
    }

    public function meeting(
        array  $params = [],
        array $additional_info = [],
        string $fieldset = 'basic'
    )
    {
        $this->validateParams($params, [
            'id' => 'required'
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer fake_bearer'
        ])->get(
            config('laravel-webex.base_url') . 'meetings/' . Arr::get($params, 'id'),
            $additional_info
        );

        return $this->formatResponse(
            $response,
            $fieldset
        );
    }
}
