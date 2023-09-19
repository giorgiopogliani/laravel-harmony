<?php

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\Newable;
use Illuminate\Support\Str;

class ActionComponent extends Component
{
    use Newable;

    protected ?string $href = null;

    protected string $title = '';

    protected ?string $method = null;

    protected array $data = [];

    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function route(...$args)
    {
        $this->href = route(...$args);

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'as')) {
            $this->method = Str::replace('As', '', $name);

            return $this;
        }

        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function toArray()
    {
        return array_merge($this->data, [
            'title' => $this->title,
            'href' => $this->href,
            'method' => $this->method,
        ]);
    }
}
