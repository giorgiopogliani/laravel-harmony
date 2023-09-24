<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\Form\Input;
use Performing\Harmony\Components\FormComponent;
use Performing\Harmony\Page;

trait ElementEdit
{
    public function edit(): Response
    {
        $id = request()->route('model');

        $model = $this->element()->query()->findOrFail($id);

        return Page::make(__('Edit') . ' ' . $this->element()->handle())
            ->form(
                FormComponent::make()
                    ->fields($this->element()->fields())
                    ->action(route($this->element()->handle() . '.update', $model))
                    ->data(
                        collect($this->element()->fields())
                            ->mapWithKeys(fn (Input $input) => [$input->name => $model->{$input->name} ])
                            ->toArray()
                    )
            )
            ->render('harmony::resources/edit');
    }
}
