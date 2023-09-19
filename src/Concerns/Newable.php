<?php

namespace Performing\Harmony\Concerns;

trait Newable
{
    public static function new()
    {
        return new static();
    }

    public static function make()
    {
        return new static();
    }
}
