<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasType;

class Dataset extends Component
{
    use HasType;

    protected array $data = [
        "showlegend" => true,
        "type" => "scatter",
        "mode" => "line"
    ];

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
