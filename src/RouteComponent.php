<?php

declare(strict_types=1);

namespace Performing\Harmony;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Performing\Harmony\Components\Component;

class RouteComponent implements Arrayable
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->hydrateProperties();
    }

    private function getProperties()
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
    }

    private function hydrateProperties()
    {
        $properties = $this->getProperties();

        foreach ($properties as $property) {
            $prop = $property->getName();
            $this->{$prop} = $this->request->input($prop);
        }
    }

    private function dehydrateProperties()
    {
        $data = [];

        foreach ($this->getProperties() as $property) {
            $prop = $property->getName();
            $data[$prop] = $this->{$prop};
        }

        return $data;
    }

    public function toArray()
    {
        $headers = $this->request->headers->all();

        if (
            array_key_exists('x-harmony-action', $headers)
            && count($headers['x-harmony-action']) >= 1
            && ($name = $headers['x-harmony-action'][0])
            && method_exists($this, $name)
        ) {
            call_user_func([$this, $name]);
        }

        return $this->dehydrateProperties();
    }
}
