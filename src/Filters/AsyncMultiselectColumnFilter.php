<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters;

use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\FilterSource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Override;

final readonly class AsyncMultiselectColumnFilter implements Filter
{
    public function __construct(
        private FilterSource $source,
        private string $column,
        private string $title,
        private string $searchUrl,
    ) {}

    #[Override]
    public function key(): string
    {
        return $this->column;
    }

    #[Override]
    public function label(): string
    {
        return $this->title;
    }

    #[Override]
    public function type(): string
    {
        return 'multiselect';
    }

    #[Override]
    public function inline(): bool
    {
        return false;
    }

    #[Override]
    public function apply(Builder $query): Builder
    {
        $raw = $this->source->get($this->key());

        if (empty($raw)) {
            return $query;
        }

        [$operator, $values] = explode('__',  $raw, 2);

        // @mago-expect analysis:mixed-return-statement
        return match ($operator) {
            // @mago-expect analysis:non-documented-method
            'in' => $query->whereIn($this->column, explode(',', $values)),
            // @mago-expect analysis:non-documented-method
            'not_in' => $query->whereNotIn($this->column, explode(',', $values)),
            default => $query,
        };
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type(),
            'inline' => $this->inline(),
            'searchUrl' => $this->searchUrl,
            'value' => $this->source->get($this->key()),
            'encoding' => 'operator',
        ];
    }
}
