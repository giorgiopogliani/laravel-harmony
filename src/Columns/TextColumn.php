<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\RenderTypes\TextRenderType;
use Illuminate\Support\Str;
use Override;
use Performing\Harmony\Concerns\CanMakeColumn;
use Performing\Harmony\Contracts\Record;

/**
 * @template T of Record
 *
 * @implements Column<T>
 */
final class TextColumn implements Column, Sortable
{
    use SortsByColumn;
    use CanMakeColumn;

    /**
     * @param  class-string<T>  $base
     */
    public function __construct(
        public string $base,
        public string $name = 'Text',
        public ?string $key = null,
    ) {}

    #[Override]
    public function key(): string
    {
        return strtolower($this->key ?? Str::slug($this->name));
    }

    #[Override]
    public function label(): string
    {
        return $this->name;
    }

    #[Override]
    public function type(): RenderType
    {
        return new TextRenderType;
    }

    /** @param T $record */
    #[Override]
    public function value(Record $record): mixed
    {
        return data_get($record->model(), $this->key());
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
