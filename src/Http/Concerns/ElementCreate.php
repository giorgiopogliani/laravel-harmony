<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\ActionComponent;
use Performing\Harmony\Components\Form\Input;
use Performing\Harmony\Components\Form\Input;
use Performing\Harmony\Components\FormComponent;
use Performing\Harmony\Page;

trait ElementCreate
{
    public function create(): Response
    {
        return Page::make(__('Create') . ' ' . $this->element()->handle())
            ->breadcrumbs([
                ActionComponent::make()
                    ->title(ucwords($this->element()->handle()))
                    ->route($this->element()->handle() . '.index'),
                ActionComponent::make()
                    ->title('Create')
                    ->route($this->element()->handle() . '.create'),
            ])
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
