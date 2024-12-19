<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Response;
use Performing\Harmony\Components\Link;
use Performing\Harmony\Components\Tables\Table;
use Performing\Harmony\Page;

trait ElementIndex
{
    public function index(): Response
    {
        return Page::make(ucwords($this->element()->handle()))
            ->breadcrumbs(
                Link::make('All ' . $this->element()->handle())
                    ->route($this->element()->handle() . '.index')
            )
            ->actions(
                ...array_filter([Route::has($this->element()->handle() . '.create')
                ? Link::make('Create ' . Str::singular($this->element()->handle()))
                    ->route($this->element()->handle() . '.create')
                    ->class('btn btn-primary')
                : null]),
            )
            ->table(
                Table::make()
                    ->rows($this->element()->query())
                    ->filters($this->element()->filters())
                    ->columns($this->element()->columns())
                    ->actions($this->element()->bulkActions())
                    ->selectable()
            )
            ->render('harmony::resources/index');
    }
}
