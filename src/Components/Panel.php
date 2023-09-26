<?php

namespace Performing\Harmony\Components;

use Illuminate\Support\Str;
use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\HasType;

class Panel extends Component
{
    use HasTitle;
    use HasType;
}
