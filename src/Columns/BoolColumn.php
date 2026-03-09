<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\Contracts\Sortable;
use Illuminate\Support\Str;
use Override;
use Performing\Harmony\Concerns\CanMakeColumn;
use Performing\Harmony\RenderTypes\BoolRenderType;

/**
 * @template T
 *
 * @implements Column<T>
 */
final class BoolColumn implements Column, Sortable
{
    use SortsByColumn;
    use CanMakeColumn;

    /**
     * @param  class-string<T>  $base
     */
    public function __construct(
        public string $base,
        public string $name,
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
        return new BoolRenderType;
    }

    #[Override]
    /** @param T $model */
    public function value(mixed $model): mixed
    {
        return data_get($model, $this->key());
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
