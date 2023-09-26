<?php

namespace Performing\Harmony\Components;

use Illuminate\Contracts\Support\Arrayable;

abstract class Component implements Arrayable
{
    protected array $data = [];

    public function getProps(): array
    {
        return [];
    }

    public function __call($name, $arguments)
    {
        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function get(string $key): ?mixed
    {
        return $this->data[$key] ?? null;
    }

    public function toArray(): array
    {
        return array_merge($this->data, $this->getProps());
    }
}
