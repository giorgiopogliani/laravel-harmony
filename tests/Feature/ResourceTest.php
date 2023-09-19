<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Inertia\Testing\AssertableInertia;

class Post extends Model
{
    use HasFactory;
}

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

// Generate tests for every endpoint in the resource controller: index, show, store, update, destroy, batchAction.
// The response is an inertia response so that according to the documentation, we can use the assertInertia method.

it('can list all resources', function () {
    $response = $this->get(route('resources.index'));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Resources/Index')
        ->has('resources'));
});

it('can show a resource', function () {
    $resource = Post::factory()->create();

    $response = $this->get(route('resources.show', $resource));

    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Resources/Show')
        ->has('resource', fn (AssertableInertia $page) => $page
            ->where('id', $resource->id)
            ->where('name', $resource->name)
            ->where('created_at', $resource->created_at->toISOString())
            ->where('updated_at', $resource->updated_at->toISOString())));
});
