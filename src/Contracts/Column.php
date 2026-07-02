<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Column
{
    public function key(): string;

    public function label(): string;

    public function value(object $record): mixed;

    public function type(): RenderType;

    public function metadata(): array;
}
