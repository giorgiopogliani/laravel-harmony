<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Performing\Harmony\Components\ConditionalComponent;

trait IsConditional
{
    public function when(bool $condition): ConditionalComponent
    {
        return new ConditionalComponent($this, $condition);
    }
}
