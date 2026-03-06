<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Illuminate\Contracts\Support\Arrayable;

interface Component extends Arrayable
{
    public function when(bool $condition);

    public function boot();

    public function getProps(): array;

    public function get(string $key): mixed;

    public function toArray();
}
