<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsComponent;

abstract class Filter implements Component
{
    use IsComponent;

    public function __construct()
    {
        $this->booting();
    }
}
