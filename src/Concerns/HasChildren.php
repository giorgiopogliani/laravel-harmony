<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

use Closure;
use Performing\Harmony\Components\Component;

trait HasChildren
{
    public function children(Component|Closure ...$children)
    {
        if (is_callable($children)) {
            $children = $children();
        }

        $this->data['children'] = array_values(array_filter($children, fn ($child) => $child->data['when'] ?? true));

        return $this;
    }

    public function getChildren(): array
    {
        return $this->data['children'] ?? [];
    }
}
