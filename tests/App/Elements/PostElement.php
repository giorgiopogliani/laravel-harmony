<?php

namespace Tests\App\Elements;

use Illuminate\Database\Eloquent\Builder;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Components\Tables\TableFilter;
use Performing\Harmony\Element;
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

    public function filters(): array
    {
        return [
            TableFilter::make('Title')->key('title')->callback(function ($query, $value) {
                $query->where('title', 'like', '%' . $value . '%');
            }),
        ];
    }

    public function fields(): array
    {
        return [
            FormField::make('title'),
            FormField::make('body'),
        ];
    }

    public function columns(): array
    {
        return [];
    }
}
