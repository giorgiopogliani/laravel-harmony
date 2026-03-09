<?php

declare(strict_types=1);

namespace Performing\Harmony\RenderTypes;

use Performing\Harmony\Contracts\RenderType;

final class BoolRenderType implements RenderType
{
    public function value(): string
    {
        return 'bool';
    }
}
