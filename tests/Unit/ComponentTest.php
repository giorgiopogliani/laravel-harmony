<?php

declare(strict_types=1);

use Performing\Harmony\Components\Panel;
use Performing\Harmony\Components\Widget;

it('returns empty array when condition is false', function () {
    $panel = Panel::make('Panel')->when(false);

    expect($panel->toArray())->toBe([]);
});

it('can set type on widget', function () {
    $widget = Widget::make('Widget')->type('counter');

    $array = $widget->toArray();

    expect($array['type'])->toBe('counter');
});
