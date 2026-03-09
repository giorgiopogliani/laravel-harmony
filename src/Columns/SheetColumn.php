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

/**
 * @template T of Linkable
 *
 * @implements Column<T>
 */
final class SheetColumn implements Column, Sortable
{
    use SortsByColumn;

    /**
     * @param  class-string<T>  $base
     */
    public function __construct(
        public string $base,
        public string $name,
        public string $extraKey,
        public string $extraLabel,
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

    #[Override]
    /** @param T $model */
    public function value(mixed $model): array
    {
        return [
            'name' => data_get($model, $this->key()),
            'href' => $model->url(),
            'extra' => [
                'label' => $this->extraLabel,
                'value' => data_get($model, $this->extraKey),
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
