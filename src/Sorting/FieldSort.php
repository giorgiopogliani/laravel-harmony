<?php

declare(strict_types=1);

namespace Performing\Harmony\Sorting;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\SortStrategy;
use Performing\Harmony\Enums\SortDirection;

final readonly class FieldSort implements SortStrategy
{
    public function __construct(
        private Field $field,
    ) {}

    public function apply(Builder $query, SortDirection $direction): void
    {
        $uuid = $this->field->identity->uuid;
        $jsonExpr = "JSON_VALUE(content, '$.\"".str_replace("'", "''", $uuid)."\"')";
        $order = $this->field->toSort();

        if ($order !== null) {
            $placeholders = implode(',', array_fill(0, count($order), '?'));

            $query->orderByRaw(
                "FIELD({$jsonExpr}, {$placeholders}) {$direction->value}",
                $order,
            );

            return;
        }

        $query->orderByRaw("{$jsonExpr} {$direction->value}");
    }
}
