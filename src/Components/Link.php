<?php

namespace Performing\Harmony\Components;

use Illuminate\Support\Str;
use Performing\Harmony\Concerns\HasTitle;

class Link extends Component
{
    use HasTitle;

    protected ?string $href = null;

    protected ?string $method = null;

    protected ?string $route = null;

    public function route(...$args)
    {
        $this->route = $args[0];
        $this->href = route(...$args);

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, ['asDelete', 'asPost', 'asPut', 'asPatch'])) {
            $this->method = Str::replace('as', '', strtolower($name));

            return $this;
        }

        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function getProps(): array
    {
        return array_filter([
            'route' => $this->route,
            'href' => $this->href,
            'method' => $this->method,
        ]);
    }
}
