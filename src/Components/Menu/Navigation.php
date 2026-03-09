<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Menu;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

class Navigation implements Component
{
    use IsConditional;

    protected ?string $title = null;

    /** @var Component[] */
    protected array $children = [];

    public function __construct(?string $title = null)
    {
        $this->title = $title;
    }

    public static function make(?string $title = null): static
    {
        return new static($title);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function children(Component ...$children): static
    {
        $this->children = array_values($children);

        return $this;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'children' => array_map(static fn (Component $c) => $c->toArray(), $this->children),
        ];
    }
}
