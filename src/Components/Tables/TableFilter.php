<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use JsonSerializable;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasKey;
use Performing\Harmony\Concerns\HasProps;
use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Prop;

class TableFilter extends Component implements Filter, JsonSerializable
{
    use HasType;
    use HasKey;
    use HasProps;

    protected string $filtersKey = '';

    protected ?Closure $query = null;

    protected mixed $default = null;

    public function __construct(string $title, ?string $key = null)
    {
        $this->data['type'] = 'text';
        parent::__construct();

        $this->data['title'] = $title;
        $this->data['key'] = $key ?? Str::of($title)->lower()->slug('_')->toString();
    }

    public static function make(string $title, ?string $key = null): static
    {
        return new static($title, $key);
    }

    public function setFiltersKey(string $key): self
    {
        $this->filtersKey = $key;

        return $this;
    }

    public function query(Closure $callback): self
    {
        $this->query = $callback;

        return $this;
    }

    public function options(array $options)
    {
        $this->data['options'] = $options;

        return $this;
    }

    public function default(mixed $default): self
    {
        $this->default = $default;

        return $this;
    }

    #[Prop('value')]
    public function getValue()
    {
        if ($this->filtersKey === '') {
            return request()->input($this->getKey(), $this->default);
        }

        return request()->input($this->filtersKey . '.' . $this->getKey(), $this->default);
    }

    #[Prop('active')]
    public function getActive()
    {
        if ($this->filtersKey === '') {
            return request()->has($this->getKey());
        }

        return request()->has($this->filtersKey . '.' . $this->getKey());
    }

    public function key(): string
    {
        return $this->getKey();
    }

    public function label(): string
    {
        return $this->data['title'];
    }

    public function type(): string
    {
        return $this->data['type'];
    }

    public function inline(): bool
    {
        return false;
    }

    public function apply(Builder $query): Builder
    {
        $value = $this->getValue();

        if (is_string($value) && is_callable($this->query)) {
            call_user_func($this->query, $query, $value);
        }

        return $query;
    }

    public function handle($query, Closure $next)
    {
        $this->apply($query);

        return $next($query);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
