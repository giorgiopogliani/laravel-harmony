<?php

namespace Performing\Harmony\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\ActionComponent;
use Performing\Harmony\Components\TableComponent;
use Performing\Harmony\Page;
use Performing\Harmony\ResourceTable;

trait HasResourceIndex
{
    abstract public function table(): ResourceTable;

    public function index(): Response
    {
        $table = $this->table();

        return Page::make('Posts')
            ->actions(
                ActionComponent::make()->title('Create')->route('posts.create'),
            )
            ->table(
                TableComponent::make()
                    ->rows($table->query())
                    ->filters($table->filters())
                    ->columns($table->columns())
                    ->actions($table->bulkActions())
                    ->selectable()
            )
            ->render('harmony::resources/index');
    }
}
