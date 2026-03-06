<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Illuminate\Contracts\Support\Arrayable;

interface Component extends Arrayable
{
    public function toArray(): array;
}
