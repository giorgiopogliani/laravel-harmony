<?php

declare(strict_types=1);

namespace Performing\Harmony\Sorting;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Performing\Harmony\Contracts\SortStrategy;
use Performing\Harmony\Enums\SortDirection;

final readonly class KeySort implements SortStrategy
{
    public function __construct(
        private string $key,
    ) {}

    public function apply(Builder $query, SortDirection $direction): void
    {
        if (! str_contains($this->key, '.')) {
            $query->orderBy($this->key, $direction->value);

            return;
        }

        [$relation, $field] = explode('.', $this->key, 2);
        $model = $query->getModel();
        $relationship = $model->{$relation}();

        if ($relationship instanceof MorphTo) {
            $this->sortByMorphTo($query, $relationship, $field, $direction);

            return;
        }

        $query->orderBy(
            $relationship->getRelated()->newQuery()
                ->select($field)
                ->whereColumn(
                    $relationship->getQualifiedForeignKeyName(),
                    $relationship->getQualifiedOwnerKeyName()
                )
                ->limit(1),
            $direction->value,
        );
    }

    private function sortByMorphTo(Builder $query, MorphTo $relationship, string $field, SortDirection $direction): void
    {
        $model = $query->getModel();
        $morphType = $relationship->getMorphType();
        $foreignKey = $relationship->getForeignKeyName();

        $alias = (clone $query)->value($morphType);

        if (! $alias) {
            return;
        }

        $morphMap = Relation::morphMap();
        $class = $morphMap[$alias] ?? $alias;
        $related = new $class;

        $query->orderBy(
            $related->newQuery()
                ->select($field)
                ->whereColumn(
                    $related->qualifyColumn($related->getKeyName()),
                    $model->qualifyColumn($foreignKey),
                )
                ->limit(1),
            $direction->value,
        );
    }
}
