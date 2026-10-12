<?php

namespace Offlineagency\LaravelWebex\Entities;

abstract class AbstractEntity
{
    /**
     * @param  array<mixed>|object|null  $parameters  Data to populate the entity. Pass null to build an empty entity.
     */
    public function __construct(array|object|null $parameters)
    {
        if (is_null($parameters)) {
            $parameters = [];
        }

        if (is_object($parameters)) {
            $parameters = get_object_vars($parameters);
        }

        $this->build($parameters);
    }

    /**
     * @param  array<mixed>  $parameters
     */
    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
