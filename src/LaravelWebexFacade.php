<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Offlineagency\LaravelWebex\Skeleton\SkeletonClass
 */
class LaravelWebexFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-webex';
    }
}
