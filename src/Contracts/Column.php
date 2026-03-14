<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use JsonSerializable;

interface Column extends JsonSerializable
{
    public function key(): string;

    public function label(): string;

    public function value(Record $record): mixed;

    public function type(): RenderType;
}
