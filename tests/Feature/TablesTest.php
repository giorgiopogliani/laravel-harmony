<?php

use Inertia\Testing\AssertableInertia;
use Tests\App\Elements\PostElement;
use Tests\App\Models\Post;

test('filters can filter', function () {

    Post::factory()->create(['title' => 'test']);
    Post::factory()->create(['title' => 'other 1']);
    Post::factory()->create(['title' => 'other 2']);
    Post::factory()->create(['title' => 'other 3']);

    $res = $this->get(route('posts.index', [
        'filters' => [
            's' => 'test',
            'status' => 'draft'
        ]
    ]));

    $element = new PostElement();

    $res->assertInertia(fn(AssertableInertia $page) => $page
        // ->component('resources/index')
        ->has('table', fn(AssertableInertia $page) => $page
            ->where('columns', to_array($element->columns()))
            ->has(
                'filters.1',
                fn(AssertableInertia $page) => $page
                    ->where('title', 'Status')
                    ->where('key', 'status')
                    ->where('options', [
                        ['value' => 'published', 'label' => 'Published'],
                        ['value' => 'draft', 'label' => 'Draft'],
                    ])
                    ->where('value', 'draft')
                    ->etc()
            )
            ->where('actions', to_array($element->bulkActions()))
            ->has('rows', fn(AssertableInertia $page) => $page
                ->has(
                    'data',
                    1,
                    fn(AssertableInertia $page) => $page
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
        ['s' => '' ],
        ['wrong2' => null],
        ['s' => '-' ],
    ];

    foreach ($filters as $filter) {
        $this->get(route('posts.index', ['filters' => $filter]))->assertOk();
    }
});
