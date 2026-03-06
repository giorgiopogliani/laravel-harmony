<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Performing\Harmony\Components\ConditionalComponent;

interface Conditional
{
    public function when(bool $condition): ConditionalComponent;
}
