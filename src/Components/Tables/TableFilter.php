<?php

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

    protected ?Closure $query = null;

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

    #[Prop('value')]
    public function getValue()
    {
        return request()->input('filters.' . $this->getKey());
    }

    public function handle($query, Closure $next)
    {
        $value = request()->input('filters.' . $this->getKey());

        if (is_string($value)) {
            call_user_func($this->query, $query, $value);
        }

        return $next($query);
    }
}
