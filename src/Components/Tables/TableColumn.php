<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Closure;
use Illuminate\Support\Str;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

final class TableColumn implements Component
{
    use IsConditional;

    protected string $title;

    protected string $key;

    protected string $type = 'text';

    protected bool $sortable = false;

    protected bool $hidden = false;

    public ?Closure $format = null;

    public ?Closure $groupAs = null;

    public function __construct(string $title, ?string $key = null)
    {
        $this->title = $title;
        $this->key = $key ?? Str::of($title)
            ->lower()
            ->slug('_')
            ->toString();
    }

    public static function make(string $title, ?string $key = null): static
    {
        return new static($title, $key);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function sortable(): static
    {
        $this->sortable = true;

        return $this;
    }

    public function hidden(): static
    {
        $this->hidden = true;

        return $this;
    }

    public function format(Closure $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function groupAs(Closure $groupAs): static
    {
        $this->groupAs = $groupAs;

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'key' => $this->key,
            'type' => $this->type,
            'sortable' => $this->sortable ?: null,
            'hidden' => $this->hidden ?: null,
        ]);
    }
}
