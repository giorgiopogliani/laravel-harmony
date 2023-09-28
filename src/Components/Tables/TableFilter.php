<?php

namespace Performing\Harmony\Components\Tables;

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

    protected ?string $key;

    protected ?Closure $callback;

    public function callback(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getCallback()
    {
        return $this->callback;
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
        $this->key = $key;

        return $this;
    }

    #[Prop('key')]
    public function getKey(): string
    {
        return $this->get('key') ?? str($this->getTitle())->slug('_')->toString();
    }
}
