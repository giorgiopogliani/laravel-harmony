<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\Forms\Form;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Components\Link;
use Performing\Harmony\Page;

trait ElementEdit
{
    public function edit(): Response
    {
        $id = request()->route('model');

        $model = $this->element()->query()->findOrFail($id);

        return Page::make(__('Edit') . ' ' . $this->element()->handle())
            ->breadcrumbs(
                Link::make('All ' . $this->element()->handle())
                    ->route($this->element()->handle() . '.index'),
                Link::make('Edit')
                    ->route($this->element()->handle() . '.edit', $model),
            )
            ->form(
                Form::make()
                    ->fields($this->element()->fields())
                    ->action(route($this->element()->handle() . '.update', $model))
                    ->data(
                        collect($this->element()->fields())
                            ->mapWithKeys(fn (FormField $input) => [$input->name => $model->{$input->name} ])
                            ->toArray()
                    )
            )
            ->render('harmony::resources/edit');
    }
}
