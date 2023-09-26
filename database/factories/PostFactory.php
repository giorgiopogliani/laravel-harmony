<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\App\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'body' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
