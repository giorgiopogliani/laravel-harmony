<?php

namespace Performing\Harmony;

use Performing\Harmony\Support\Menu;
use Spatie\Flash\Flash;

class Harmony
{
    protected $menu;

    public function __construct()
    {
        $this->menu = new Support\Menu();
    }

    public function menu(): Menu
    {
        return $this->menu;
    }

    public function flash(): Flash
    {
        return app(Flash::class);
    }
}
