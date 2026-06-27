<?php

declare(strict_types=1);

namespace Performing\Harmony\RenderTypes;

use Performing\Harmony\Contracts\RenderType;
use Override;

final class ActionsRenderType implements RenderType
{
    #[Override]
    public function value(): string
    {
        return 'Buttons';
    }
}
