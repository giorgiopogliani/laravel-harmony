<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Closure;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Illuminate\Support\Str;
use Override;
use Performing\Harmony\Components\Link;
use Performing\Harmony\RenderTypes\ActionsRenderType;

/**
 * @template T
 *
 * @implements Column<T>
 */
final class ActionsColumn implements Column
{
    /**
     * @param  class-string<T>  $base
     * @param  Closure(T): array<string, Link> $links
     */
    public function __construct(
        public string $base,
        public string $name = 'Text',
        public ?string $key = null,
        public Closure $links = [],
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
        return new ActionsRenderType;
    }

    #[Override]
    /** @param T $model */
    public function value(mixed $model): mixed
    {
        return call_user_func($this->links, $model);
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
