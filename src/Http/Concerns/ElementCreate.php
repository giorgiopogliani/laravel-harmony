<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\Form\Input;
use Performing\Harmony\Components\FormComponent;
use Performing\Harmony\Page;

trait ElementCreate
{
    public function create(): Response
    {
        return Page::make(__('Create') . ' ' . $this->element()->handle())
            ->form(
                FormComponent::make()
                    ->fields($this->element()->fields())
                    ->data(
                        collect($this->element()->fields())
                            ->mapWithKeys(fn (Input $input) => [$input->name => ''])
                            ->toArray()
                    )
                    ->action(route($this->element()->handle() . '.store'))
            )
            ->render('harmony::resources/create');
    }
}
