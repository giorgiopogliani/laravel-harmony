<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\SortStrategy;
use Performing\Harmony\Sorting\KeySort;

/** @require-implements \Performing\Harmony\Contracts\Column */
trait IsKeySortable
{
    public function sortable(): SortStrategy
    {
        return new KeySort($this->key());
    }
}
