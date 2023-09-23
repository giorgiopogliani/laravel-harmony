<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\FormComponent;
use Performing\Harmony\Page;

trait ElementEdit
{
    public function edit(): Response
    {
        return Page::make(__('Edit') . ' ' . $this->element()->handle())
            ->form(
                FormComponent::make()->fields($this->element()->fields())
            )
            ->render('resources/create');
    }
}
