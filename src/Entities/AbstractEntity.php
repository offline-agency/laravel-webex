<?php

namespace Offlineagency\LaravelWebex\Entities;

use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

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
        if ($type === null) {
            return true;
        }
        if ($value === null) {
            return $type->allowsNull();
        }

        // a union (int|float|null) accepts a value one of its members accepts
        $members = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];
        foreach ($members as $member) {
            if ($member instanceof ReflectionNamedType && self::fits($member->getName(), $value)) {
                return true;
            }
        }

        return false;
    }

    private static function fits(string $type, mixed $value): bool
    {
        $checks = ['mixed' => fn (): bool => true, 'string' => is_string(...), 'int' => is_int(...), 'float' => is_float(...), 'bool' => is_bool(...), 'array' => is_array(...), 'object' => is_object(...), 'null' => is_null(...)];
        $check = $checks[$type] ?? fn (mixed $value): bool => is_object($value) && is_a($value, $type);

        return $check($value);
    }
}
