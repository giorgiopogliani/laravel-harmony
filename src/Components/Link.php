<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\IsConditional;

class Link implements Component
{
    use IsConditional;

    protected ?string $title = null;

    protected ?string $href = null;

    protected ?string $method = null;

    protected ?string $route = null;

    protected ?string $icon = null;

    protected ?string $confirm = null;

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

    public function asDelete(): static
    {
        $this->method = 'delete';

        return $this;
    }

    public function asPost(): static
    {
        $this->method = 'post';

        return $this;
    }

    public function asPut(): static
    {
        $this->method = 'put';

        return $this;
    }

    public function asPatch(): static
    {
        $this->method = 'patch';

        return $this;
    }

    public function route(string ...$args): static
    {
        $this->route = $args[0];
        $this->href = route(...$args);

        return $this;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function confirm(string $message): static
    {
        $this->confirm = $message;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'href' => $this->href,
            'method' => $this->method,
            'route' => $this->route,
            'icon' => $this->icon,
            'confirm' => $this->confirm,
        ]);
    }
}
