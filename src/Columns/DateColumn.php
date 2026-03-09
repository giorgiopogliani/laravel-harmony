<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\Enums\SortDirection;
use Performing\Harmony\RenderTypes\TextRenderType;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Override;

/**
 * @template T
 * @implements Column<T>
 */
final class DateColumn implements Column, Sortable
{
    /**
     * @param  class-string<T>  $base
     */
    public function __construct(
        public string $base,
        public string $name,
        public ?string $key = null,
        public string $format = 'd M Y',
    ) {}

    #[Override]
    public function key(): string
    {
        return strtolower($this->key ?? Str::slug($this->name));
    }

    #[Override]
    public function label(): string
    {
        return $this->name;
    }

    #[Override]
    public function type(): RenderType
    {
        return new TextRenderType;
    }

    #[Override]
    /** @param T $model */
    public function value(mixed $model): mixed
    {
        $value = data_get($model, $this->key());

        if ($value instanceof CarbonInterface) {
            return $value->format($this->format);
        }

        return $value;
    }

    #[Override]
    public function sort(Builder $query, SortDirection $direction): QueryBuilder
    {
        // @mago-expect analysis:non-documented-method
        // @mago-expect analysis:mixed-return-statement
        return $query->orderBy($this->key(), $direction->value);
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
