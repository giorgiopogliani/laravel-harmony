<?php

use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia;
use Tests\App\Elements\PostElement;
use Tests\App\Http\Controllers\PostController;
use Tests\App\Models\Post;
use Tests\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);

    Route::harmoned('posts', PostController::class);
});

it('can list all resources', function () {
    $this->withoutExceptionHandling();

    Post::factory()->count(23)->create();

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
    $post = Post::factory()->create();

    $this->withoutExceptionHandling();

    $response = $this->get(route('posts.show', $post));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        // ->component('resources/show')
        ->has('element', fn (AssertableInertia $page) => $page
            ->where('id', $post->id)
            ->where('title', $post->title)
            ->etc()));
});

it('can store a resource', function () {
    $this->withoutExceptionHandling();

    $this->post(route('posts.store'), [
        'title' => 'New Title',
        'body' => 'New Body',
    ])
    ->assertRedirect()
    ->assertSessionHas('laravel_flash_message', [
        'message' => 'Created successfully.',
        'level' => 'success',
        'class' => null,
    ]);

    $post = Post::first();

    expect($post->title)->toBe('New Title');
});

it('can update a resource', function () {
    $post = Post::factory()->create();

    $this->withoutExceptionHandling();

    $this->put(route('posts.update', $post), [
        'id' => $post->id,
        'title' => 'New Title',
        'body' => 'New Body',
    ])
    ->assertRedirect()
    ->assertSessionHas('laravel_flash_message', [
        'message' => 'Updated successfully.',
        'level' => 'success',
        'class' => null,
    ]);

    $post = Post::where('id', $post->id)->first();

    expect($post->title)->toBe('New Title');
});

it('can delete a resource', function () {
    $post = Post::factory()->create();

    $this->withoutExceptionHandling();

    $this->delete(route('posts.destroy', $post), [
        'id' => $post->id,
        'title' => 'New Title',
        'body' => 'New Body',
    ])
    ->assertRedirect()
    ->assertSessionHas('laravel_flash_message', [
        'message' => 'Deleted successfully.',
        'level' => 'success',
        'class' => null,
    ]);

    $post = Post::where('id', $post->id)->first();

    expect($post)->toBeNull();
});
