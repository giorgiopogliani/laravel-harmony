<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Closure;
use Performing\Harmony\Components\Component;

trait HasChildren
{
    public function children(Component|Closure ...$children)
    {
        $this->data['children'] = $children;

        return $this;
    }

    public function getChildren(): array
    {
        return $this->data['children'] ?? [];
    }
}
