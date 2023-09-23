<?php

namespace Performing\Harmony\Support;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Performing\Harmony\Concerns\Newable;

class Menu implements Arrayable
{
    use Newable;

    protected array|Closure $items = [];

    public function items(array|Closure $items)
    {
        $this->items = $items;

        return $this;
    }

    public function toArray()
    {
        $items = $this->items;

        return [
            'items' => array_map(function ($item) {
                if ($item instanceof Arrayable) {
                    return $item->toArray();
                }

                return $item;
            }, is_callable($items) ? $items() : $items)
        ];
    }
}
