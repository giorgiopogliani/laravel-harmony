<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Inertia\Inertia;
use Inertia\Response;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

class Page implements Component
{
    use IsConditional;

    protected ?string $title = null;

    protected array $breadcrumbs = [];

    protected array $actions = [];

    protected array $additional = [];

    public function __construct(?string $title = null)
    {
        $this->title = $title;
    }

    public static function make(?string $title = null): static
    {
        return new static($title);
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function breadcrumbs(mixed ...$breadcrumbs): static
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
    }

    public function actions(mixed ...$actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function additional(array $data): static
    {
        $this->additional = array_merge($this->additional, $data);

        return $this;
    }

    public function render(string $component, array $data = []): Response
    {
        $acc = [];
        foreach ($data as $key => $value) {
            $acc[$key] = $value instanceof Component ? $value->toArray() : $value;
        }

        return Inertia::render($component, array_merge($this->toArray(), $acc));
    }

    public function toArray(): array
    {
        return array_merge([
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs,
            'actions' => array_map(
                fn ($action) => $action instanceof Component ? $action->toArray() : $action,
                $this->actions,
            ),
        ], $this->additional);
    }
}
