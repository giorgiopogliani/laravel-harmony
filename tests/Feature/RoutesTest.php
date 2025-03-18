<?php

use function Pest\Laravel\post;

test('counter increments', function () {
    $this->withoutExceptionHandling();

    $data = post('/harmony/components/counter', [ 'count' => 1 ], [ 'x-harmony-action' => 'increment' ]);

    expect($data->status())->toBe(200);
    expect($data->json('count'))->toBe(2);
});

test('counter decrements', function () {
    $this->withoutExceptionHandling();

    $data = post('/harmony/components/counter', [ 'count' => 5 ], [ 'x-harmony-action' => 'decrement' ]);

    expect($data->status())->toBe(200);
    expect($data->json('count'))->toBe(4);
});
