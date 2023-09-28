<?php

namespace Performing\Harmony\Components\Menu;

use Illuminate\Contracts\Support\Arrayable;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasChildren;
use Performing\Harmony\Concerns\HasTitle;

class Menu extends Component
{
    use HasTitle;
    use HasChildren;
}
