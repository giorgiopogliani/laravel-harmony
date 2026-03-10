<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Override;
use Performing\Harmony\Concerns\IsConditional;

class Link implements Component
{
    use IsConditional;

    protected array $data = [];

    public function __construct(
        protected ?string $title = null,
        protected ?string $href = null,
        protected ?string $method = null,
        protected ?string $route = null,
        protected ?string $icon = null,
        protected ?string $confirm = null,
        protected ?string $target = null,
        protected ?string $as = null,
        protected ?string $variant = null,
        protected bool $download = false,
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

    public function method(string $method): static
    {
        $this->method = $method;

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

    public function confirm(string|bool $message): static
    {
        if (! is_string($message)) {
            $this->confirm = 'Are you sure?';
            return $this;
        }

        $this->confirm = $message;

        return $this;
    }

    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function as(string $name): static
    {
        $this->as = $name;

        return $this;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function variant(mixed $value): static
    {
        $this->variant = $value;

        return $this;
    }

    public function download(bool $name = true): static
    {
        $this->download = $name;

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->data[$name] = $arguments[0];

        return $this;
    }

    #[Override]
    public function toArray(): array
    {
        if (method_exists($this, 'boot')) {
            $this->boot();
        }

        return array_filter([
            'title' => $this->title,
            'href' => $this->href,
            'method' => $this->method,
            'route' => $this->route,
            'icon' => $this->icon,
            'target' => $this->target,
            'as' => $this->as,
            'confirm' => $this->confirm,
            'variant' => $this->variant,
            'download' => $this->download,
            ...$this->data,
        ]);
    }
}
