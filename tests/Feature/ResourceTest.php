<?php

use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia;
use Tests\App\Elements\PostElement;
use Tests\App\Http\Controllers\PostController;
use Tests\App\Models\Post;
use Tests\App\Models\User;

beforeEach(function () {
    $this->user = User::first();

    $this->actingAs($this->user);

    Route::harmoned('posts', PostController::class);
});

it('can list all resources', function () {
    $this->withoutExceptionHandling();

    $element = new PostElement();

    $response = $this->get(route('posts.index', [
        'table_page' => 2,
        'table' => [
            'per_page' => 11
        ],
    ]));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        // ->component('resources/index')
        ->has('table', fn (AssertableInertia $page) => $page
            ->where('columns', $element->columns())
            ->where('filters', $element->filters())
            ->where('actions', $element->bulkActions())
            ->has('rows', fn (AssertableInertia $page) => $page
                ->has('data', 11)
                ->where('current_page', 2)
                ->etc())
            ->etc()));
});

it('can show a resource', function () {
    $post = Post::first();

    $this->withoutExceptionHandling();

    $response = $this->get(route('posts.show', $post));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        // ->component('resources/show')
        ->has('element', fn (AssertableInertia $page) => $page
            ->where('id', $post->id)
            ->where('title', $post->title)
            ->where('created_at', $post->created_at)
            ->where('updated_at', $post->updated_at)
            ->etc()));
});
