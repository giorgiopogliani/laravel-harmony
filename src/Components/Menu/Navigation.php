<?php

namespace Performing\Harmony\Support;

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
