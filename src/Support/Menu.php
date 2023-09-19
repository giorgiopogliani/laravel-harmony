<?php

namespace Performing\Harmony\Support;

use Illuminate\Contracts\Support\Arrayable;
use Performing\Harmony\Concerns\Newable;

class Menu implements Arrayable
{
    use Newable;

    protected array $items = [];

    public function items(array $items)
    {
        $this->items = $items;

        return $this;
    }

    public function toArray()
    {
        return [
            'items' => array_map(fn ($item) => $item->toArray(), $this->items)
        ];
    }
}
