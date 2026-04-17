<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\Sortable;
use JsonSerializable;
use Override;

/**
 * @template T of Column
 *
 * @implements Column<T>
 */
final class ColumnResource implements JsonSerializable
{
    public function __construct(
        public Column $column,
    ) {}

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->column->key(),
            'title' => $this->column->label(),
            'type' => $this->column->type()->value(),
            'sortable' => $this->column instanceof Sortable
        ];
    }
}
