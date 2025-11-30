<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Closure;
use Illuminate\Support\Str;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasKey;
use Performing\Harmony\Concerns\HasProps;
use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Prop;

class TableFilter extends Component
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
        return request()->input($this->filtersKey . '.' .$this->getKey(), $this->default);
    }

    #[Prop('active')]
    public function getActive()
    {
        return request()->has($this->filtersKey . '.' . $this->data['key']);
    }

    public function handle($query, Closure $next)
    {
        $value = $this->getValue();

        if (is_string($value) && is_callable($this->query)) {
            call_user_func($this->query, $query, $value);
        }

        return $next($query);
    }
}
