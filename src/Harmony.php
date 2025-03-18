<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

    public function registerRoute(string $name, string $component): void
    {
        Route::post("/harmony/components/$name", fn (Request $request) => new $component($request))->middleware('web');
    }
}
