<?php

namespace Performing\Harmony\Components\Form;

use Performing\Harmony\Components\Component;
use Illuminate\Support\Str;

class Input extends Component
{
    public string $label;

    public string $name;

    public string $type = 'text';

    public string $help = '';

    public string $validation = '';

    public array $options = [];

    public function __construct(string $label, ?string $name = null)
    {
        $this->label = $label;
        $this->name = $name ?? Str::of($label)->lower()->slug('_')->toString();
    }

    public static function make($label, $name = null): self
    {
        return new static($label, $name);
    }

    public function help(string $help)
    {
        $this->help = $help;

        return $this;
    }

    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function validation(string $validation)
    {
        $this->validation = $validation;

        return $this;
    }

    public function toData(): array
    {
        return [$this->name => ''];
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'placeholder' => $this->label,
            'label' => ucfirst($this->label),
            'help' => $this->help,
            'validation' => $this->validation,
            'options' => $this->options,
        ];
    }
}
