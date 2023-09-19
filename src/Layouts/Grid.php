<?php

namespace Performing\Harmony\Layouts;

use Illuminate\Contracts\Support\Arrayable;

class Grid implements Arrayable
{
    protected $columns = 1;

    protected $children = [];

    public static function new()
    {
        return new static();
    }

    public function columns(int $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function children(...$children)
    {
        $this->children = $children;

        return $this;
    }

    public function toArray()
    {
        return [
            'type' => 'grid',
            'columns' => $this->columns,
            'children' => $this->children,
        ];
    }
}
