<?php

declare(strict_types=1);

namespace Performing\Harmony\RenderTypes;

use Override;
use Performing\Harmony\Contracts\RenderType;

final class TextRenderType implements RenderType
{
    #[Override]
    public function value(): string
    {
        return 'text';
    }
}
