<?php

namespace Performing\Harmony\Components\Table;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasProps;
use Performing\Harmony\Concerns\HasTitle;
use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Prop;

class TableFilter extends Component
{
    use HasTitle;
    use HasType;
    use HasProps;

    protected array $options = [];

    protected array $operators = [];

    protected ?Closure $callback = null;

    public function callback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    #[Prop('options')]
    public function getOptions(): array
    {
        return $this->options;
    }

    public function operators($operators)
    {
        $this->operators = $operators;

        return $this;
    }

    #[Prop('operators')]
    public function getOperators(): array
    {
        return $this->operators;
    }

    public function key(string $key): self
    {
        $this->data['key'] = $key;

        return $this;
    }

    public function getKey(): string
    {
        return $this->get('key') ?? str($this->getTitle())->slug('_')->toString();
    }

    public function apply(Builder $query): void
    {
        if ($this->callback) {
            call_user_func(\Closure::bind($this->callback, $this), $query);
        } else {
            $query->where($this->getKey(), $this->getSqlOperator(), $this->getSqlValue());
        }
    }
}
