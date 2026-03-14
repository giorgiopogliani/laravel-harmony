<?php

declare(strict_types=1);

namespace Performing\Harmony\Columns;

use Closure;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\RenderType;
use Illuminate\Support\Str;
use Override;
use Performing\Harmony\Components\Link;
use Performing\Harmony\Contracts\Record;
use Performing\Harmony\RenderTypes\ActionsRenderType;

/**
 * @template T of Record
 * @implements Column<T>
 */
final class ActionsColumn implements Column
{
    /**
     * @param Closure(T): list<Link> $links
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

    /** @param T $record */
    #[Override]
    public function value(Record $record): mixed
    {
        // @mago-expect analysis:mixed-argument
        return collect(call_user_func($this->links, $record))->values()->toArray();
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
