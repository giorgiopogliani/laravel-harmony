<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Menu;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\IsComponent;

class MenuItem implements Component
{
    use IsComponent;
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
            'href' => array_key_exists('route', $this->data) ? route($this->data['route']) : '',
        ];
    }
}
