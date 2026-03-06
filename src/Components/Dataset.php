<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Concerns\IsComponent;

class Dataset implements Component
{
    use IsComponent;
    use HasType;

    public function __construct()
    {
        $this->data['showlegend'] = true;
        $this->data['type'] = 'scatter';
        $this->data['mode'] = 'line';
        $this->booting();
    }

    public static function make(string $name): static
    {
        $dataset = new static();
        return $dataset->name($name);
    }

    public function name(string $name)
    {
        $this->data['name'] = $name;
        $this->data["hovertemplate"] = "Name=$name";
        return $this;
    }

    public function type(string $type)
    {
        $this->data['type'] = $type;
        return $this;
    }
}
