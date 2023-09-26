<?php

namespace Tests\App\Elements;

use Illuminate\Database\Eloquent\Builder;
use Performing\Harmony\Components\Form\Input;
use Performing\Harmony\Data;
use Performing\Harmony\Element;
use Tests\App\Data\PostData;
use Tests\App\Models\Post;

class PostElement extends Element
{
    public function query(): Builder
    {
        return Post::query();
    }

    public function handle(): string
    {
        return 'posts';
    }

    public function fields(): array
    {
        return [
            Input::make('title'),
            Input::make('body'),
        ];
    }

    public function filters(): array
    {
        return [

        ];
    }

    public function columns(): array
    {
        return [

        ];
    }

    public function bulkActions(): array
    {
        return [

        ];
    }
}
