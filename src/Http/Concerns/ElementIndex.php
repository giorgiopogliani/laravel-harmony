<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\ActionComponent;
use Performing\Harmony\Components\TableComponent;
use Performing\Harmony\Page;

trait ElementIndex
{
    public function index(): Response
    {
        return Page::make(ucwords($this->element()->handle()))
            ->breadcrumbs(
                ActionComponent::make()
                    ->title('Create ' . $this->element()->handle())
                    ->route($this->element()->handle() . '.index')
            )
            ->actions(
                ActionComponent::make()
                    ->title('Create ' . $this->element()->handle())
                    ->route($this->element()->handle() . '.create')
                    ->class('btn btn-primary'),
            )
            ->table(
                TableComponent::make()
                    ->rows($this->element()->query())
                    ->filters($this->element()->filters())
                    ->columns($this->element()->columns())
                    ->actions($this->element()->bulkActions())
                    ->selectable()
            )
            ->render('harmony::resources/index');
    }
}
