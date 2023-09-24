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
        return Page::make($this->element()->handle())
            ->actions(
                ActionComponent::make()->title('Create ' . $this->element()->handle())->route($this->element()->handle() . '.create'),
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
