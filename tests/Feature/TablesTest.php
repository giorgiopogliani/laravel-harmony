<?php

use Inertia\Testing\AssertableInertia;
use Tests\App\Elements\PostElement;
use Tests\App\Models\Post;

test('filters can filter', function () {

    $this->withoutExceptionHandling();

    Post::factory()->create(['title' => 'test']);
    Post::factory()->create(['title' => 'other 1']);
    Post::factory()->create(['title' => 'other 2']);
    Post::factory()->create(['title' => 'other 3']);

    $element = new PostElement();

    $res = $this->get(route('posts.index', [
        'filters' => [
            'title' => [
                'value' => 'test',
                'operator' => 'contains',
            ],
        ]
    ]));

    $res->assertInertia(fn (AssertableInertia $page) => $page
        // ->component('resources/index')
        ->has('table', fn (AssertableInertia $page) => $page
            ->where('columns', to_array($element->columns()))
            ->has(
                'filters',
                1,
                fn (AssertableInertia $page) => $page
                    ->where('title', 'Title')
                    ->where('key', 'title')
                    ->where('type', 'text')
                    ->etc()
            )
            ->where('actions', to_array($element->bulkActions()))
            ->has('rows', fn (AssertableInertia $page) => $page
                ->has(
                    'data',
                    1,
                    fn (AssertableInertia $page) => $page
                        ->where('title', 'test')
                        ->etc()
                )
                ->where('current_page', 1)
                ->etc())
            ->etc()));
});


test('filters dont crash with wrong parameters', function () {
    $this->withoutExceptionHandling();

    $filters = [
        ['title' => [
            'operator' => 'contains',
        ]],
        ['title' => [
            'operator' => ['contains'],
            'value' => ['contains'],
        ]],
        ['wrong2' => []],
        ['wrong' => [
            'operator' => 'test',
            'value' => 'contains',
        ]],
        ['-' => [
            'operator' => '.',
            'value' => 'contains',
        ]],
        ['title' => [
            'operator' => '.',
            'value' => '/\\',
        ]],
    ];

    foreach ($filters as $filter) {
        $this->get(route('posts.index', ['filters' => $filter]))->assertOk();
    }
});
