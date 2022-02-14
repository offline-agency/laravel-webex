<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as Validation;

class BaseEntity
{
    protected function formatResponse(
        $response,
        string $fieldset,
        string $property = null
    )
    {
        switch ($fieldset) {
            case 'basic':
                $items = json_decode(
                    $response->body()
                );

                return $property
                    ? $items->$property
                    : $items;
            case 'complete':
                return json_decode(
                    $response->body()
                );
            case 'original':
                return $response->body();
        }
    }


    protected function validateParams(
        array $params,
        array $rules
    ): Validation
    {
        return Validator::make(
            $params,
            $rules
        );
    }
}
