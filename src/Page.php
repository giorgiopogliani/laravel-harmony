<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Inertia\Inertia;
use Illuminate\Support\Traits\Macroable;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasTitle;

class Page extends Component
{
    use HasTitle;
    use Macroable;

    public function breadcrumbs(...$breadcrumbs)
    {
        $this->data['breadcrumbs'] = $breadcrumbs;

        return $this;
    }

    public function actions(...$actions)
    {
        $this->data['actions'] = $actions;

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->data[$name] = $arguments[0];

        return $this;
    }

    public function additional(array $data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function render($component, $data = [])
    {
        $acc = [];
        foreach ($data as $key => $value) {
            $acc[$key] = is_subclass_of($value, Component::class) ? $value->toArray() : $value;
        }

        return Inertia::render($component, array_merge($this->toArray(), $acc));
    }
}
