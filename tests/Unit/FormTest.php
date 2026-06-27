<?php

declare(strict_types=1);

use Performing\Harmony\Components\Forms\Form;
use Performing\Harmony\Components\Forms\FormField;

it('can create a form with make', function () {
    $form = Form::make();

    expect($form->toArray())->toBe([
        'fields' => [],
        'data' => [],
        'action' => '',
    ]);
});

it('can set form action', function () {
    $form = Form::make()->action('/posts');

    expect($form->toArray()['action'])->toBe('/posts');
});

it('can set form data', function () {
    $form = Form::make()->data(['title' => 'Hello']);

    expect($form->toArray()['data'])->toBe(['title' => 'Hello']);
});

it('can set form fields', function () {
    $fields = [
        FormField::make('Title'),
        FormField::make('Body'),
    ];

    $form = Form::make()->fields($fields);

    expect($form->toArray()['fields'])->toHaveCount(2);
});

it('creates a form field with label and generated name', function () {
    $field = FormField::make('First Name');

    $array = $field->toArray();

    expect($array['name'])
        ->toBe('first_name')
        ->and($array['label'])
        ->toBe('First Name')
        ->and($array['placeholder'])
        ->toBe('First Name')
        ->and($array['type'])
        ->toBe('text');
});

it('accepts a custom name for form field', function () {
    $field = FormField::make('Title', 'post_title');

    expect($field->toArray()['name'])->toBe('post_title');
});

it('can set field type', function () {
    $field = FormField::make('Description')->type('textarea');

    expect($field->toArray()['type'])->toBe('textarea');
});

it('can set field help text', function () {
    $field = FormField::make('Email')->help('Enter your email');

    expect($field->toArray()['help'])->toBe('Enter your email');
});

it('can set field options', function () {
    $options = ['draft' => 'Draft', 'published' => 'Published'];
    $field = FormField::make('Status')->options($options);

    expect($field->toArray()['options'])->toBe($options);
});

it('can set field rules', function () {
    $field = FormField::make('Email')->rules(['required', 'email']);

    expect($field->toArray()['rules'])->toBe(['required', 'email']);
});

it('returns empty data for toData', function () {
    $field = FormField::make('Title');

    expect($field->toData())->toBe(['title' => '']);
});
