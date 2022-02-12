<?php

namespace Offlineagency\LaravelWebex\Entities;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BaseEntity
{
    protected function validateParams(
        array $params,
        array $rules
    ): array
    {
        try {
            return Validator::make(
                $params,
                $rules
            )->validate();
        } catch (ValidationException $e) {
            return $e->errors();
        }
    }

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
}
