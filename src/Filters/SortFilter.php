<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\FilterSource;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\Enums\SortDirection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Override;

final readonly class SortFilter implements Filter
{
    /** @var array<string, Column> */
    private array $columns;

    /**
     * @param  list<Column>  $columns
     */
    public function __construct(
        private FilterSource $source,
        array $columns,
    ) {
        $indexed = [];
        foreach ($columns as $column) {
            $indexed[$column->key()] = $column;
        }
        $this->columns = $indexed;
    }

    #[Override]
    public function key(): string
    {
        return 'sort';
    }

    #[Override]
    public function label(): string
    {
        return 'Sort';
    }

    #[Override]
    public function type(): string
    {
        return 'sort';
    }

    #[Override]
    public function inline(): bool
    {
        return true;
    }

    #[Override]
    public function apply(Builder $query): Builder
    {
        $value = $this->source->get($this->key());

        if (empty($value)) {
            return $query;
        }

        $pairs = explode(',', $value);

        foreach ($pairs as $pair) {
            $parts = explode(':', $pair, 2);

            if (count($parts) !== 2) {
                continue;
            }

            [$key, $dir] = $parts;
            $direction = SortDirection::tryFrom($dir);

            if ($direction === null) {
                continue;
            }

            $column = $this->columns[$key] ?? null;

            if ($column instanceof Sortable) {
                $column->sort($query, $direction);
            }
        }

        return $query;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type(),
            'inline' => $this->inline(),
            'value' => $this->source->get($this->key()),
            'encoding' => 'plain',
            'options' => array_map(
                static fn (Column $column) => ['value' => $column->key(), 'label' => $column->label()],
                array_values($this->columns),
            ),
        ];
    }
}
