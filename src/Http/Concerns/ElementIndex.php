<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\TableComponent;
use Performing\Harmony\Page;

trait ElementIndex
{
    public function index(): Response
    {
        return Page::make($this->element()->handle())
            ->table(
                TableComponent::make()
                    ->rows($this->element()->query())
                    ->filters($this->element()->filters())
                    ->columns($this->element()->columns())
                    ->actions($this->element()->bulkActions())
                    ->selectable()
            )
            ->render('resources/index');
    }
}
