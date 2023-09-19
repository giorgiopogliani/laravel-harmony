<?php

namespace Performing\Harmony\Concerns;

use Inertia\Response;
use Performing\Harmony\Components\ActionComponent;
use Performing\Harmony\Components\FormComponent;
use Performing\Harmony\Page;

trait HasResourceEdit
{
    public function create(): Response
    {
        $form = $this->form();

        return Page::make('Create Post')
            ->actions(
                ActionComponent::make()->title('Back')->route('posts.index'),
            )
            ->form(
                FormComponent::make()
                    ->fields($form->fields())
                    ->data($form->data())
                    ->action(route('posts.store'))
            )
            ->render('harmony::resources/create');
    }

    public function edit(): Response
    {
        $form = $this->form();

        return Page::make('Edit Post')
            ->actions(
                ActionComponent::make()->title('Back')->route('posts.index'),
            )
            ->form(
                FormComponent::make()
                    ->fields($form->fields())
                    ->data($form->data())
                    ->action(route('posts.store'))
            )
            ->render('harmony::resources/edit');
    }
}
