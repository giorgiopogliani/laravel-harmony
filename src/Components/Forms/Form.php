<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Forms;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsConditional;

final class Form implements Component
{
    use IsConditional;

    protected array $fields = [];

    protected array $formData = [];

    protected string $action = '';

    public function __construct() {}

    public static function make(): static
    {
        return new static();
    }

    public function data(array $data): static
    {
        $this->formData = $data;

        return $this;
    }

    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'fields' => $this->fields,
            'data' => $this->formData,
            'action' => $this->action,
        ];
    }
}
