<?php

namespace Performing\Harmony;

use Inertia\Inertia;
use Performing\Harmony\Components\Component;

class Page extends Component
{
    protected $data = [];

    public static function make(string $title)
    {
        return new static($title);
    }

    public function __construct(string $title)
    {
        $this->data['title'] = $title;
    }

    public function actions(...$actions)
    {
        $this->data['actions'] = $actions;

        return $this;
    }

    public function table($table)
    {
        $this->data['table'] = $table;

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function render($component)
    {
        return Inertia::render($component, $this->toArray());
    }
}
