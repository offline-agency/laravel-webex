<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Meetings extends BaseEntity
{
    public function meetings(
        array  $params = [],
        array  $additional_info = [],
        string $fieldset = 'basic'
    )
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer fake_bearer'
        ])->get(
            config('laravel-webex.base_url') . 'meetings',
            $additional_info
        );

        return $this->formatResponse(
            $response,
            $fieldset,
            'items'
        );
    }

    public function meeting(
        array  $params = [],
        array  $additional_info = [],
        string $fieldset = 'basic'
    )
    {
        $validator = $this->validateParams($params, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->messages();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer MWE1YWJmYWUtNWY4MS00ZjkwLWFjMzUtOGYyYjZiMzJlZTExOTNjMDQxY2ItZDk1_PE93_33d69f74-a9c9-41be-80ba-7fbca5cbedc8'
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
