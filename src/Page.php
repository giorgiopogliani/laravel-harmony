<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Inertia\Inertia;
use Inertia\Response;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Components\Filters\Filter;
use Performing\Harmony\Concerns\IsConditional;

class Page implements Component
{
    use IsConditional;

    protected ?string $title = null;

    /** @var Component[] */
    protected array $breadcrumbs = [];

    /** @var Component[] */
    protected array $actions = [];

    /** @var Filter[] */
    protected array $filters = [];

    /** @var array<string, mixed> */
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

    public function breadcrumbs(Component ...$breadcrumbs): static
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
    }

    public function actions(Component ...$actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function actionIf(bool $condition, Component $action): static
    {
        if (! $condition) {
            return $this;
        }

        $this->actions[] = $action;

        return $this;
    }

    /** @param array<string, mixed> $data */
    public function render(string $component, array $data = []): Response
    {
        $acc = [];
        foreach ($data as $key => $value) {
            $acc[$key] = $value instanceof Component ? $value->toArray() : $value;
        }

        return Inertia::render($component, array_merge($this->toArray(), $acc));
    }

    public function __call($name, $arguments)
    {
        $this->additional[$name] = $arguments[0];

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        return array_merge([
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs,
            'actions' => $this->actions,
            'filters' => $this->filters,
        ], $this->additional);
    }
}
