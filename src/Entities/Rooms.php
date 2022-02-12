<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Facades\Http;

class Rooms
{
    public function getRoomsList(
        array $params = [],
        string $fieldset = 'basic'
    )
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer NzQ3ZmU2ZjYtNDU1MC00NWEyLWEzMTMtODJjNWVlZDA3NWY2NjAxZDg5MTYtZWI2_PE93_33d69f74-a9c9-41be-80ba-7fbca5cbedc8'
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

    private function formatResponse(
        $response,
        string $fieldset,
        string $property
    )
    {
        switch ($fieldset) {
            case 'basic':
                $items = json_decode(
                    $response->body()
                );

                return $items->$property;
            case 'complete':
                return json_decode(
                    $response->body()
                );
            case 'original':
                return $response->body();
        }
    }
}
