<?php

namespace Performing\Harmony\Support;

use Illuminate\Contracts\Support\Arrayable;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasChildren;
use Performing\Harmony\Concerns\HasTitle;

class Menu extends Component
{
    use HasTitle;
    use HasChildren;

    public function getProps(): array
    {
        return [];
    }
}
