<?php

declare(strict_types=1);

use Performing\Harmony\Components\Panel;
use Performing\Harmony\Components\Widget;

it('sets when to true by default', function () {
    $panel = Panel::make('Panel');

    expect($panel->toArray())->toHaveKey('when', true);
});

it('returns empty array when condition is false', function () {
    $panel = Panel::make('Panel')->when(false);

    expect($panel->toArray())->toBe([]);
});

it('can get a value by key', function () {
    $panel = Panel::make('Test Panel');

    expect($panel->get('title'))->toBe('Test Panel');
});

it('returns null for missing keys', function () {
    $panel = Panel::make('Panel');

    expect($panel->get('nonexistent'))->toBeNull();
});

it('can set arbitrary data via magic call', function () {
    $widget = Widget::make('Widget')
        ->type('counter')
        ->value(42);

    $array = $widget->toArray();

    expect($array['type'])->toBe('counter')
        ->and($array['value'])->toBe(42);
});

it('throws when magic call has no arguments', function () {
    Widget::make('Widget')->someMethod();
})->throws(Exception::class);

it('resolves nested components in toArray', function () {
    $inner = Panel::make('Inner');
    $outer = Panel::make('Outer')->child($inner);

    $array = $outer->toArray();

    expect($array['child'])->toBe($inner->toArray());
});
