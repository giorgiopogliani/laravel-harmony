<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Concerns\IsConditional;

class Panel extends Component
{
    use HasTitle;
    use HasType;
    use IsConditional;
}
