<?php

namespace Performing\Harmony\Components\Menu;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasTitle;

class MenuItem extends Component
{
    use HasTitle;

    public function route(string $route)
    {
        $this->data['route'] = $route;

        return $this;
    }

    public function icon(string $icon)
    {
        $this->data['icon'] = $icon;

        return $this;
    }

    public function getProps(): array
    {
        return [
            'href' => route($this->data['route']) ?? '',
        ];
    }
}
