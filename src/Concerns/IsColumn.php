<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Illuminate\Support\Str;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\RenderTypes\TextRenderType;

/**
 * @require-implements Column
 */
trait IsColumn
{
    public function key(): string
    {
        return Str::of($this->label())
            ->lower()
            ->slug()
            ->toString();
    }

    public function type(): RenderType
    {
        return new TextRenderType();
    }

    public function metadata(): array
    {
        return [];
    }
}
