<?php

declare(strict_types=1);

namespace Performing\Harmony\Sorting;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Contracts\SortStrategy;
use Performing\Harmony\Enums\SortDirection;

final readonly class ClosureSort implements SortStrategy
{
    public function __construct(
        private Closure $callback,
    ) {}

    public function apply(Builder $query, SortDirection $direction): void
    {
        ($this->callback)($query, $direction->value);
    }
}
