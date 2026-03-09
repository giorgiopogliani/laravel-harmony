<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Performing\Harmony\Enums\SortDirection;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;

interface Sortable
{
    public function sort(EloquentBuilder $query, SortDirection $direction): QueryBuilder;
}
