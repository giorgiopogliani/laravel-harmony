<?php

namespace Performing\Harmony;

use Performing\Harmony\Components\Menu\Navigation;
use Spatie\Flash\Flash;

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

    public function flash(): Flash
    {
        return app(Flash::class);
    }
}
