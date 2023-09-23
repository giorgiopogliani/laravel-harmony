<?php

namespace Performing\Harmony\Http\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\FormComponent;
use Performing\Harmony\Page;

trait ElementCreate
{
    public function create(): Response
    {
        return Page::make(__('Create') . ' ' . $this->element()->handle())
            ->form(
                FormComponent::make()->fields($this->element()->fields())
            )
            ->render('resources/create');
    }
}
