<?php

namespace Performing\Harmony\Concerns;

use Inertia\Inertia;

trait ResourceListable
{
    public function index()
    {
        return Inertia::render('harmony::resources/index', [
            'title' => $this->title(),
            'table' => [
                'rows' => fn () => $this->query()->paginate(),
                'columns' => $this->columns(),
                'filters' => [],
                'query' => []
            ]
        ]);
    }

    abstract public function columns(): array;
}
