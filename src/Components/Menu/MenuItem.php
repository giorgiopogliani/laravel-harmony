<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Menu;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

class MenuItem implements Component
{
    use IsConditional;

    protected ?string $title = null;

    protected ?string $route = null;

    protected ?string $icon = null;

    protected ?string $badge = null;

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

    public function route(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function badge(string $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'route' => $this->route,
            'href' => $this->route ? route($this->route) : null,
            'icon' => $this->icon,
            'badge' => $this->badge,
        ]);
    }
}
