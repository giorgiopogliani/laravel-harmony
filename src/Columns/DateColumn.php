<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Performing\Harmony\RenderTypes\TextRenderType;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use Override;
use Performing\Harmony\Concerns\CanMakeColumn;
use Performing\Harmony\Contracts\Record;

/**
 * @template T of Record
 * @implements Column<T>
 */
final class DateColumn implements Column, Sortable
{
    use CanMakeColumn;
    use IsKeySortable;

    public function __construct(
        public string $name = 'Date',
        public ?string $key = null,
        public string $format = 'd M Y',
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
        $value = data_get($record->model(), $this->key());

        if ($value instanceof CarbonInterface) {
            return $value->format($this->format);
        }

        return $value;
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
