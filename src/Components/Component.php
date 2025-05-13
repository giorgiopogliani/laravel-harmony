<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use ReflectionClass;

abstract class Component implements Arrayable
{
    use Conditionable;

    protected array $data = [];

    public function __construct()
    {
        $this->booting();
    }

    private function booting()
    {
        $reflect = new ReflectionClass($this);

        foreach ($reflect->getTraits() as $trait) {
            if ($trait->hasMethod('boot' . $trait->getShortName())) {
                $this->{'boot' . $trait->getShortName()}();
            }
        }

        $this->boot();
    }

    public function boot()
    {
    }

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

    public function toArray()
    {
        return array_merge($this->data, $this->getProps());
    }
}
