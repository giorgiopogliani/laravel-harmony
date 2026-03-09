<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables;

use Illuminate\Contracts\Pagination\Paginator;
use Performing\Harmony\Contracts\DataTable;

/**
 * @require-implements DataTable
 */
trait HasPaginatedQuery
{
    public function rows(): Paginator
    {
        return $this
            ->query()
            ->paginate()
            ->withQueryString()
            ->through(function (mixed $model) {
                $row = ['id' => $model->getKey()];
                foreach ($this->columns() as $column) {
                    $row[$column->key()] = $column->value($model);
                }
                return $row;
            });
    }
}
