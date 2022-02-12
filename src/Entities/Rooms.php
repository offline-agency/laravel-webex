<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Facades\Http;

class Rooms extends BaseEntity
{
    public function getRoomsList(
        array $params = [],
        string $fieldset = 'basic'
    )
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer fake_bearer'
        ])->get(
            config('laravel-webex.base_url') . 'rooms',
            $params
        );

        return $this->formatResponse(
            $response,
            $fieldset,
            'items'
        );
    }
}
