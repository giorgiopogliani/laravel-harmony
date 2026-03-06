<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\IsComponent;

class Widget implements Component
{
    use IsComponent;
    use HasTitle;

    public function type(string $type)
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function getProps(): array
    {
        return [];
    }
}
