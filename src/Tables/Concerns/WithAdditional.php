<?php

declare(strict_types=1);

namespace Performing\Harmony\Tables\Concerns;

use Performing\Harmony\Columns\ColumnResource;
use Performing\Harmony\Contracts\Column;

/**
 * @require-implement DataTable
 */
trait WithAdditional
{
    public function additional(): array
    {
        return [
            ...$this->source->additional(),
            'columns' => array_map(static fn (Column $column) => new ColumnResource($column), $this->columns()),
            'attributes' => $this->attributes(),
            'filters' => $this->filters->all(),
        ];
    }
}
