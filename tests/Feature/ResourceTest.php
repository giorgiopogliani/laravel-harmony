<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia;
use Tests\App\Elements\PostElement;
use Tests\App\Models\Post;

it('can list all resources', function () {
    $this->withoutExceptionHandling();

    Post::factory()->count(100)->create();

    $element = new PostElement();

    $response = $this->get(route('posts.index', [
        'page' => 2,
        'per_page' => 40
    ]));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        // ->component('resources/index')
        ->has('table', fn (AssertableInertia $page) => $page
            ->where('columns', to_array($element->columns()))
            ->has('rows', fn (AssertableInertia $page) => $page
                ->has('data', 40)
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

it('can edit a resource', function () {
    $post = Post::factory()->create();

    $this->withoutExceptionHandling();

    $response = $this
        ->get(route('posts.edit', $post))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            // ->component('resources/show')
            ->has(
                'form',
                fn (AssertableInertia $page) => $page
                    ->has('fields')
                    ->has(
                        'data',
                        fn (AssertableInertia $page) => $page
                            ->where('title', $post->title)
                            ->where('body', $post->body)
                    )
                    ->where('action', route('posts.update', $post))
            )
            ->etc());
});

it('can create a resource', function () {
    $this->withoutExceptionHandling();

    $this
        ->get(route('posts.create'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            // ->component('resources/show')
            ->has(
                'form',
                fn (AssertableInertia $page) => $page
                    ->has('fields')
                    ->has(
                        'data',
                        fn (AssertableInertia $page) => $page
                            ->where('title', '')
                            ->where('body', '')
                    )
                    ->where('action', route('posts.store'))
            )
            ->etc());

    $this->post(route('posts.store'), [
        'title' => 'New Title',
        'body' => 'New Body',
    ]);

    $post = Post::first();

    expect($post->title)->toBe('New Title');
    expect($post->body)->toBe('New Body');
});

it('can store a resource', function () {
    $this->withoutExceptionHandling();

    $this->post(route('posts.store'), [
        'title' => 'New Title',
        'body' => 'New Body',
    ])
        ->assertRedirect()
        ->assertSessionHas('flash_notification');

    expect(session('flash_notification.0.message'))->toBe('Created successfully.');
    expect(session('flash_notification.0.level'))->toBe('success');

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
        ->assertSessionHas('flash_notification');

    expect(session('flash_notification.0.message'))->toBe('Updated successfully.');
    expect(session('flash_notification.0.level'))->toBe('success');

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
        ->assertSessionHas('flash_notification');

    expect(session('flash_notification.0.message'))->toBe('Deleted successfully.');
    expect(session('flash_notification.0.level'))->toBe('success');

    $post = Post::where('id', $post->id)->first();

    expect($post)->toBeNull();
});
