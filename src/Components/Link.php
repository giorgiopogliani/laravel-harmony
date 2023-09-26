<?php

namespace Performing\Harmony\Components;

use Illuminate\Support\Str;
use Performing\Harmony\Concerns\HasTitle;

class ActionComponent extends Component
{
    use HasTitle;

    protected ?string $href = null;

    protected ?string $method = null;

    public function route(...$args)
    {
        $this->href = route(...$args);

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'as') && strlen($name) > 2) {
            $this->method = Str::replace('as', '', strtolower($name));

            return $this;
        }

        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function getProps(): array
    {
        return [
            'href' => $this->href,
            'method' => $this->method,
        ];
    }
}
