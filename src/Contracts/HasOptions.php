<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface HasOptions
{
    /** @return list<array{ label: string, value: string, color: string, order: int }> */
    public function getOptions(): array;
}
