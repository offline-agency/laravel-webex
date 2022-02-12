<?php

namespace Offlineagency\LaravelWebex\Entities;

class BaseEntity
{
    protected function formatResponse(
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
