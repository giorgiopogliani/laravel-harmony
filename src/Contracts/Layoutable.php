<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Support\Collection;

interface Layoutable
{
    /** @return Collection<int, Field> */
    public function getFields(): Collection;

    public function getType(): string;

    /** @return mixed */
    public function getKey();
}
