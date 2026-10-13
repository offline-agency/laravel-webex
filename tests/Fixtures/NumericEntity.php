<?php

namespace Offlineagency\LaravelWebex\Tests\Fixtures;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

/**
 * An entity with a union-typed, a mixed and an untyped property (entities defined by applications).
 */
final class NumericEntity extends AbstractEntity
{
    public int|float|null $count = null;

    public mixed $anything = null;

    /** @var mixed */
    public $untyped;
}
