<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\TableComponent;
use Performing\Harmony\Page;
use Tests\App\Data\PostData;

trait ElementShow
{
    public function show(): Response
    {
        $id = request()->route('model');

        $model = $this->element()->query()->findOrFail($id);

        return Page::make($this->element()->handle())
            ->element($model->toArray())
            ->render('resources/show');
    }
}
