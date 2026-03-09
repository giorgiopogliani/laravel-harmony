<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use JsonSerializable;

/**
 * @template T
 */
interface Column extends JsonSerializable
{
    public function key(): string;

    public function label(): string;

    /** @param T $model */
    public function value(mixed $model): mixed;

    public function type(): RenderType;
}
