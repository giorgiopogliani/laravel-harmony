<?php

namespace Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sushi\Sushi;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
}
