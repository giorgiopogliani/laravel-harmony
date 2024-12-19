<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Performing\Harmony\Components\Menu\Navigation;

class Harmony
{
    protected $menu;

    public function __construct()
    {
        $this->menu = Navigation::make('');
    }

    public function menu(): Navigation
    {
        return $this->menu;
    }
}
