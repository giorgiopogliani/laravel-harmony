<?php

declare(strict_types=1);

namespace Performing\Harmony\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Performing\Harmony\Harmony
 *
 * @method static \Performing\Harmony\Support\Menu menu()
 */
class Harmony extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Performing\Harmony\Harmony::class;
    }
}
