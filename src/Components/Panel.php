<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\HasType;

class Panel extends Component
{
    use HasTitle;
    use HasType;
}
