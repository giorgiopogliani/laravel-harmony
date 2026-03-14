<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\Linkable;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\RenderTypes\SheetRenderType;
use Illuminate\Support\Str;
use Override;
use Performing\Harmony\Concerns\CanMakeColumn;
use Performing\Harmony\Contracts\Record;

/**
 * @template T of Linkable&Record
 *
 * @implements Column<T>
 */
final class SheetColumn implements Column, Sortable
{
    use CanMakeColumn;
    use IsKeySortable;

    public function __construct(
        public string $name,
        public string $extraKey = '',
        public string $extraLabel = '',
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
        return new SheetRenderType;
    }

    /** @param T $record */
    #[Override]
    public function value(Record $record): array
    {
        return [
            'name' => data_get($record, $this->key()),
            'href' => $record->url(),
            'extra' => [
                'label' => $this->extraLabel,
                'value' => data_get($record, $this->extraKey),
            ],
        ];
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type()->value(),
            'wrap' => true,
        ];
    }
}
