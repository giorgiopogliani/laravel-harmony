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
        if (!isset($arguments[0])) {
            throw new \Exception("Component method $name called with no arguments", 1);
        }

        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function toArray(): array
    {
        return array_merge($this->data, $this->getProps());
    }
}
