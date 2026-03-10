<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Menu;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasChildren;
use Performing\Harmony\Concerns\HasTitle;

class Navigation extends Component
{
    use HasTitle;
    use HasChildren;

    public function getProps(): array
    {
        return [];
    }
}
