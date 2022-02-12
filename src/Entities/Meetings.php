<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Facades\Http;

class Meetings extends BaseEntity
{
    public function getMeetingsList(
        array $params = [],
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
}
