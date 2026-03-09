<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Override;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\Contentable;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\Enums\SortDirection;

/**
 * @template T of Contentable
 *
 * @implements Column<T>
 */
final class FieldColumn implements Column, Sortable
{
    /**
     * @param  class-string<T>  $base
     */
    public function __construct(
        public string $base,
        public Field $field,
    ) {}

    #[Override]
    public function key(): string
    {
        return $this->field->identity->handle;
    }

    #[Override]
    public function label(): string
    {
        return $this->field->identity->name;
    }

    #[Override]
    public function type(): RenderType
    {
        return $this->field->identity->type;
    }

    /** @param T $model */
    #[Override]
    public function value(mixed $model): mixed
    {
        return $model->getFieldValue($this->field)?->toContent();
    }

    #[Override]
    public function sort(Builder $query, SortDirection $direction): Builder
    {
        $uuid = $this->field->identity->uuid;
        $jsonExpr = "JSON_VALUE(content, '$.\"".str_replace("'", "''", $uuid)."\"')";
        $order = $this->field->toSort();

        if ($order !== null) {
            $placeholders = implode(',', array_fill(0, count($order), '?'));

            return $query->orderByRaw(
                "FIELD({$jsonExpr}, {$placeholders}) {$direction->value}",
                $order,
            );
        }

        return $query->orderByRaw("{$jsonExpr} {$direction->value}");
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type()->value(),
        ];
    }
}
