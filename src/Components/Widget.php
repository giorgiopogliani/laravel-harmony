<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasTitle;

class Widget extends Component
{
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
