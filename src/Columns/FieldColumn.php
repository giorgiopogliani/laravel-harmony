<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Override;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\Contentable;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\Contracts\SortStrategy;
use Performing\Harmony\Sorting\FieldSort;

/**
 * @template T of Contentable
 *
 * @implements Column<T>
 */
final class FieldColumn implements Column, Sortable
{
    public function __construct(
        public Field $field,
    ) {}

    #[Override]
    public function key(): string
    {
        return $this->field->identity->handle;
    }

    #[Override]
    public function label(): string
    {
        return $this->field->identity->name;
    }

    #[Override]
    public function type(): RenderType
    {
        return $this->field->identity->type;
    }

    /** @param T $record */
    #[Override]
    public function value(object $record): mixed
    {
        return $record->getFieldValue($this->field)?->toContent();
    }

    #[Override]
    public function sortable(): SortStrategy
    {
        return new FieldSort($this->field);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type()->value(),
        ];
    }
}
