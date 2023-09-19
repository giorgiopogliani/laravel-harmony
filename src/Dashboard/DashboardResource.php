<?php

namespace Performing\Harmony\Dashboard;

use Inertia\Inertia;
use Performing\Harmony\Components\HeaderComponent;
use Performing\Harmony\Resource;

abstract class DashboardResource extends Resource
{
    public function components(): array
    {
        return [
            'title' => $this->title(),
            'widgets' => $this->widgets()
        ];
    }

    public function index()
    {
        return Inertia::render('harmony::dashboard', [
            'title' => $this->title(),
            ...$this->components(),
        ]);
    }

    abstract public function widgets(): array;
}
