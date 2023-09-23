<?php

namespace Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sushi\Sushi;

class Post extends Model
{
    use HasFactory;
    use Sushi;

    public function getRows()
    {
        return collect()->times(30, fn ($index) => [
            'id' => $index,
            'title' => fake()->sentence(),
            'user_id' => User::first()->id,
            'body' => fake()->paragraph(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])
        ->toArray();
    }
}
