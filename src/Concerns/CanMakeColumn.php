<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Deprecated;

/**
 * @require-implement Column
 */
trait CanMakeColumn
{
    #[Deprecated]
    public static function make(string $name, ?string $key = null)
    {
        return new static(name: $name, key: $key);
    }
}
