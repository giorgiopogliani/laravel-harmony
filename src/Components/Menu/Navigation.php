<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Menu;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasChildren;
use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\IsComponent;

class Navigation implements Component
{
    use IsComponent;
    use HasTitle;
    use HasChildren;

    public function getProps(): array
    {
        return [];
    }
}
