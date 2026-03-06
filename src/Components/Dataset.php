<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\IsConditional;

class Dataset implements Component
{
    use IsConditional;

    protected string $name = '';

    protected string $type = 'scatter';

    protected string $mode = 'line';

    protected bool $showlegend = true;

    protected string $hovertemplate = '';

    protected array $x = [];

    protected array $y = [];

    public function __construct() {}

    public static function make(string $name): static
    {
        $dataset = new static();

        return $dataset->name($name);
    }

    public function name(string $name): static
    {
        $this->name = $name;
        $this->hovertemplate = "Name=$name";

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function mode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function showlegend(bool $showlegend): static
    {
        $this->showlegend = $showlegend;

        return $this;
    }

    public function x(array $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function y(array $y): static
    {
        $this->y = $y;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'mode' => $this->mode,
            'showlegend' => $this->showlegend,
            'hovertemplate' => $this->hovertemplate,
            'x' => $this->x,
            'y' => $this->y,
        ];
    }
}
