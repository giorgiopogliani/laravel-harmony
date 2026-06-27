<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Performing\Harmony\Enums\SortDirection;

interface SortStrategy
{
    public function apply(Builder $query, SortDirection $direction): void;
}
