<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

/**
 * @template T
 */
interface Record
{
    /** @return T */
    public function model(): mixed;
}
