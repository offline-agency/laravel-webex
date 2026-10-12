<?php

namespace Offlineagency\LaravelWebex\Entities;

use ReflectionNamedType;
use ReflectionProperty;

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
            if (is_string($property) && property_exists($this, $property) && self::accepts(new ReflectionProperty($this, $property), $value)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Whether $value fits the declared type of $property: a field Webex returns with another type than the entity
     * declares is ignored (the property keeps its default) instead of raising a TypeError.
     */
    private static function accepts(ReflectionProperty $property, mixed $value): bool
    {
        $type = $property->getType();
        if (! $type instanceof ReflectionNamedType || $type->getName() === 'mixed') {
            return true;
        }
        if ($value === null) {
            return $type->allowsNull();
        }

        $checks = ['string' => is_string(...), 'int' => is_int(...), 'float' => is_numeric(...), 'bool' => is_bool(...), 'array' => is_array(...), 'object' => is_object(...)];
        $check = $checks[$type->getName()] ?? fn (mixed $value): bool => is_object($value) && is_a($value, $type->getName());

        return $check($value);
    }
}
