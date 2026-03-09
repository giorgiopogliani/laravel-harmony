<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters;

use Illuminate\Contracts\Support\Arrayable;

abstract class FilterType implements Arrayable, FilterableType
{
    use FilterScope;

    #[\Override]
    public function name(): string
    {
        return str($this->label())->slug('_')->toString();
    }

    #[\Override]
    public function type(): string
    {
        return 'text';
    }

    #[\Override]
    public function props(): array
    {
        $props = [];
        $reflection = new \ReflectionObject($this);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(FilterTypeProp::class);

            if (count($attributes) > 0) {
                $props[$method->getName()] = $this->{$method->getName()}();
            }
        }

        return $props;
    }

    #[\Override]
    public function toArray()
    {
        return [
            'name' => $this->name(),
            'label' => $this->label(),
            'props' => $this->props(),
            'operators' => $this->operators(),
        ];
    }
}
