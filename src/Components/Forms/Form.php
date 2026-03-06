<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Forms;

use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\IsComponent;

class Form implements Component
{
    use IsComponent;

    protected array $fields = [];

    protected array $formData = [];

    protected string $action = '';

    public function __construct()
    {
        $this->booting();
    }

    public static function make()
    {
        return new static();
    }

    public function data($data)
    {
        $this->formData = $data;

        return $this;
    }

    public function fields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'fields' => $this->fields,
            'data' => $this->formData,
            'action' => $this->action,
        ];
    }
}
