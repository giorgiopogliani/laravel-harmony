<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Forms;

use Illuminate\Support\Str;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

class FormField implements Component
{
    use IsConditional;

    protected string $label;

    protected string $name;

    protected string $type = 'text';

    protected string $help = '';

    protected string|array $rules = '';

    protected array $options = [];

    public function __construct(string $label, ?string $name = null)
    {
        $this->label = $label;
        $this->name = $name ?? Str::of($label)
            ->lower()
            ->slug('_')
            ->toString();
    }

    public static function make(string $label, ?string $name = null): static
    {
        return new static($label, $name);
    }

    public function help(string $help): static
    {
        $this->help = $help;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function options(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function rules(array|string $rules): static
    {
        $this->rules = $rules;

        return $this;
    }

    public function toData(): array
    {
        return [$this->name => ''];
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'placeholder' => $this->label,
            'label' => ucfirst($this->label),
            'help' => $this->help,
            'rules' => $this->rules,
            'options' => $this->options,
        ];
    }
}
