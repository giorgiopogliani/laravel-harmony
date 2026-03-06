<?php

declare(strict_types=1);

use Performing\Harmony\Components\Chart;
use Performing\Harmony\Components\Dataset;

it('can set a chart title', function () {
    $chart = Chart::make()->title('Revenue');

    $array = $chart->toArray();

    expect($array['layout'])->toBe(['title' => ['text' => 'Revenue']]);
});

it('can set datasets', function () {
    $datasets = [
        Dataset::make('Series A')->x([1, 2, 3])->y([10, 20, 30]),
    ];

    $chart = Chart::make()->datasets($datasets);

    $array = $chart->toArray();

    expect($array['dataset'])->toHaveCount(1);
});

it('can create a dataset with make', function () {
    $dataset = Dataset::make('Sales');

    expect($dataset)->toBeInstanceOf(Dataset::class);
});

it('can set data on dataset', function () {
    $dataset = Dataset::make('Data')->x([1, 2, 3])->y([10, 20, 30]);

    expect($dataset)->toBeInstanceOf(Dataset::class);
});
