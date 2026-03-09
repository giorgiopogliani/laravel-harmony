<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Enums\SortDirection;
use Performing\Harmony\Contracts\Column;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

/** @require-implements Column */
trait SortsByColumn
{
    public function sort(Builder $query, SortDirection $direction): QueryBuilder
    {
        $key = $this->key();

        if (! str_contains($key, '.')) {
            // @mago-expect analysis:non-documented-method
            // @mago-expect analysis:mixed-return-statement
            return $query->orderBy($key, $direction->value);
        }

        [$relation, $field] = explode('.', $key, 2);
        $model = $query->getModel();
        $relationship = $model->{$relation}();

        if ($relationship instanceof MorphTo) {
            return $this->sortByMorphTo($query, $relationship, $field, $direction);
        }

        // @mago-expect analysis:non-documented-method
        // @mago-expect analysis:mixed-return-statement
        return $query->orderBy(
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

    private function sortByMorphTo(Builder $query, MorphTo $relationship, string $field, SortDirection $direction): Builder
    {
        $model = $query->getModel();
        $morphType = $relationship->getMorphType();
        $foreignKey = $relationship->getForeignKeyName();

        $alias = (clone $query)->value($morphType);

        if (! $alias) {
            return $query;
        }

        $morphMap = Relation::morphMap();
        $class = $morphMap[$alias] ?? $alias;
        $related = new $class;

        // @mago-expect analysis:non-documented-method
        // @mago-expect analysis:mixed-return-statement
        return $query->orderBy(
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
