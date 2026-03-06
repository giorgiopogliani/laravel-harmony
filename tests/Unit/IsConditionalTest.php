<?php

declare(strict_types=1);

use Performing\Harmony\Components\ConditionalComponent;
use Performing\Harmony\Components\Panel;

it('returns a ConditionalComponent from when', function () {
    $result = Panel::make('Test')->when(true);

    expect($result)->toBeInstanceOf(ConditionalComponent::class);
});
