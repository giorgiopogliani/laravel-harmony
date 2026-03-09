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
     * @param Closure(T): array<string, Link> $links
     */
    public function __construct(
        public string $name,
        public Closure $links,
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
        return new ActionsRenderType;
    }

    #[Override]
    /** @param T $model */
    public function value(mixed $model): mixed
    {
        return collect(call_user_func($this->links, $model))->toArray();
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
