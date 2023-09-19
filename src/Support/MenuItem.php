<?php

namespace Performing\Harmony\Support;

use Illuminate\Contracts\Support\Arrayable;

class MenuItem implements Arrayable
{
    public string $title;

    public string $route;

    public string $icon;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function make(string $title)
    {
        return new static($title);
    }

    public function route(string $route)
    {
        $this->route = $route;

        return $this;
    }

    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'link' => route($this->route),
            'route' => $this->route,
            'icon' => $this->icon,
        ];
    }
}
