<?php

declare(strict_types=1);

use Performing\Harmony\Components\Link;
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
    $home = Link::make('Home')->href('/');
    $posts = Link::make('Posts')->href('/posts');
    $edit = Link::make('Edit')->href('/posts/1/edit');

    $page = Page::make('Page')
        ->breadcrumbs($home, $posts, $edit);

    $array = $page->toArray();

    expect($array['breadcrumbs'])->toHaveCount(3)
        ->and($array['breadcrumbs'][0]->toArray()['title'])->toBe('Home')
        ->and($array['breadcrumbs'][1]->toArray()['title'])->toBe('Posts')
        ->and($array['breadcrumbs'][2]->toArray()['title'])->toBe('Edit');
});

it('can set actions', function () {
    $action1 = Link::make('Action 1')->href('/action1');
    $action2 = Link::make('Action 2')->href('/action2');

    $page = Page::make('Page')
        ->actions($action1, $action2);

    $array = $page->toArray();

    expect($array['actions'])->toHaveCount(2)
        ->and($array['actions'][0]->toArray()['title'])->toBe('Action 1')
        ->and($array['actions'][1]->toArray()['title'])->toBe('Action 2');
});

it('can set additional data', function () {
    $page = Page::make('Page')
        ->additional(['foo' => 'bar', 'baz' => 123]);

    $array = $page->toArray();

    expect($array['foo'])->toBe('bar')->and($array['baz'])->toBe(123);
});

it('returns empty array when condition is false', function () {
    $page = Page::make('Page')
        ->when(false);

    expect($page->toArray())->toBe([]);
});
