<?php

namespace Performing\Harmony\Concerns;

use Inertia\Inertia;

trait AsDashboardResoruce
{
    public function index()
    {
        return Inertia::render('harmony::dashboard', [
            'title' => $this->title(),
            'widgets' => $this->widgets(),
        ]);
    }
}
