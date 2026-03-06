<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

trait HasMake
{
    public function __construct()
    {
        $this->booting();
    }

    public static function make()
    {
        return new static();
    }
}
