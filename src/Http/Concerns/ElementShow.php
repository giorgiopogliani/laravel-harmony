<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Page;

trait ElementShow
{
    public function show(): Response
    {
        $id = request()->route('model');

        $model = $this->element()->query()->findOrFail($id);

        return Page::make($this->element()->handle())
            ->element($model->toArray())
            ->render('harmony::resources/show');
    }
}
