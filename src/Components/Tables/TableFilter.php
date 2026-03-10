<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

class TableFilter implements Component
{
    use IsConditional;

    protected string $title;

    protected string $key;

    protected string $type = 'text';

    protected ?Closure $query = null;

    protected mixed $default = null;

    protected array $options = [];

    protected mixed $value = null;

    protected bool $active = false;

    protected array $data = [];

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

    public function query(Closure $callback): static
    {
        $this->query = $callback;

        return $this;
    }

    public function options(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function default(mixed $default): static
    {
        $this->default = $default;

        return $this;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function resolve(mixed $value, bool $active): static
    {
        $this->value = $value;
        $this->active = $active;

        return $this;
    }

    public function handle(Builder $builder, Closure $next): mixed
    {
        if (is_string($this->value) && is_callable($this->query)) {
            call_user_func($this->query, $builder, $this->value);
        }

        return $next($builder);
    }

    public function __call($name, $arguments)
    {
        $this->data[$name] = $arguments[0];

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'key' => $this->key,
            'type' => $this->type,
            'options' => $this->options ?: null,
            'value' => $this->value,
            'active' => $this->active ?: null,
            ...$this->data,
        ]);
    }
}
