<?php

declare(strict_types=1);

use Performing\Harmony\Page;

it('can be created with make', function () {
    $page = Page::make('My Page');

    expect($page->getTitle())->toBe('My Page');
});

it('can be created without a title', function () {
    $page = Page::make();

    expect($page->getTitle())->toBeNull();
});

it('can set breadcrumbs', function () {
    $page = Page::make('Page')
        ->breadcrumbs('Home', 'Posts', 'Edit');

    $array = $page->toArray();

    expect($array['breadcrumbs'])->toBe(['Home', 'Posts', 'Edit']);
});

it('can set actions', function () {
    $page = Page::make('Page')
        ->actions('action1', 'action2');

    $array = $page->toArray();

    expect($array['actions'])->toBe(['action1', 'action2']);
});

it('can set additional data', function () {
    $page = Page::make('Page')
        ->additional(['foo' => 'bar', 'baz' => 123]);

    $array = $page->toArray();

    expect($array['foo'])->toBe('bar')
        ->and($array['baz'])->toBe(123);
});

it('returns empty array when condition is false', function () {
    $page = Page::make('Page')
        ->when(false);

    expect($page->toArray())->toBe([]);
});
