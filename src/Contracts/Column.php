<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use JsonSerializable;

/**
 * @template T of Record
 */
interface Column extends JsonSerializable
{
    public function key(): string;

    public function label(): string;

    /** @param T $record */
    public function value(Record $record): mixed;

    public function type(): RenderType;
}
