<?php

namespace Performing\Harmony\Concerns;

trait HasMake
{
    public static function make()
    {
        return new static();
    }
}
