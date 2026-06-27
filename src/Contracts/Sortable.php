<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Sortable
{
    public function sortable(): SortStrategy;
}
