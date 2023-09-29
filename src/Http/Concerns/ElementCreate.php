<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Support\Str;
use Inertia\Response;
use Performing\Harmony\Components\Link;
use Performing\Harmony\Components\Forms\Form;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Page;

trait ElementCreate
{
    public function create(): Response
    {
        return Page::make(__('Create') . ' ' . Str::singular($this->element()->handle()))
            ->breadcrumbs(
                Link::make('All ' . $this->element()->handle())
                    ->route($this->element()->handle() . '.index'),
                Link::make('Create')
                    ->route($this->element()->handle() . '.create'),
            )
            ->form(
                Form::make()
                    ->fields($this->element()->fields())
                    ->data(
                        collect($this->element()->fields())
                            ->mapWithKeys(fn (FormField $input) => [$input->name => ''])
                            ->toArray()
                    )
                    ->action(route($this->element()->handle() . '.store'))
            )
            ->render('harmony::resources/create');
    }
}
