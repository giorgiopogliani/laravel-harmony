<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Concerns\IsComponent;

class Panel implements Component
{
    use IsComponent;
    use HasTitle;
    use HasType;
}
