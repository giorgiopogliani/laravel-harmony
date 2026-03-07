<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Override;
use Performing\Harmony\Concerns\IsConditional;

final class Link implements Component
{
    use IsConditional;

    public function __construct(
        protected ?string $title = null,
        protected ?string $href = null,
        protected ?string $method = null,
        protected ?string $route = null,
        protected ?string $icon = null,
        protected ?string $confirm = null,
    ) {}

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

    public function href(string $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function route(string $name, mixed $parameters = [], bool $absolute = true): static
    {
        $this->route = $name;
        $this->href = route($name, $parameters, $absolute);
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

    #[Override]
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
