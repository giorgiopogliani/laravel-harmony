<?php

namespace Performing\Harmony\Concerns;

use Performing\Harmony\Prop;

trait HasProps
{
    public function getProps(): array
    {
        $props = [];
        $reflection = new \ReflectionObject($this);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(Prop::class);
            if (count($attributes) > 0) {
                $key = $attributes[0]->newInstance()->key ?? $method->getName();
                $props[$key] = $this->{$method->getName()}();
            }
        }

        return $props;
    }
}
