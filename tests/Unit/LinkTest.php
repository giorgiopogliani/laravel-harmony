<?php

declare(strict_types=1);

use Performing\Harmony\Components\Link;

it('can be created with make', function () {
    $link = Link::make('Edit');

    expect($link->getTitle())->toBe('Edit');
});

it('can set http method to delete', function () {
    $link = Link::make('Delete')->asDelete();

    $array = $link->toArray();

    expect($array['method'])->toBe('delete');
});

it('can set http method to post', function () {
    $link = Link::make('Create')->asPost();

    $array = $link->toArray();

    expect($array['method'])->toBe('post');
});

it('can set http method to put', function () {
    $link = Link::make('Update')->asPut();

    $array = $link->toArray();

    expect($array['method'])->toBe('put');
});

it('can set http method to patch', function () {
    $link = Link::make('Patch')->asPatch();

    $array = $link->toArray();

    expect($array['method'])->toBe('patch');
});

it('excludes null props from array', function () {
    $link = Link::make('Link');

    $array = $link->toArray();

    expect($array)->not->toHaveKey('href')
        ->and($array)->not->toHaveKey('method')
        ->and($array)->not->toHaveKey('route');
});

it('can set arbitrary data via magic call', function () {
    $link = Link::make('Edit')->icon('pencil');

    $array = $link->toArray();

    expect($array['icon'])->toBe('pencil');
});
