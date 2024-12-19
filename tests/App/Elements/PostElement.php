<?php

declare(strict_types=1);

namespace Tests\App\Elements;

use Illuminate\Database\Eloquent\Builder;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Components\Tables\TableColumn;
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
            TableFilter::make('Search', 's')->query(function ($query, $value) {
                $query->where('title', 'like', '%' . $value . '%');
            }),
            TableFilter::make('Status', 'status')
                ->options([
                    ['value' => 'published', 'label' => 'Published'],
                    ['value' => 'draft', 'label' => 'Draft'],
                ])
                ->query(function ($query, $value) {
                    //$query->where('status', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            TableColumn::make('Title'),
            TableColumn::make('Body'),
        ];
    }

    public function fields(): array
    {
        return [
            FormField::make('Title'),
            FormField::make('Body'),
        ];
    }
}
