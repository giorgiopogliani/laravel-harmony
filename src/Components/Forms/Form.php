<?php

namespace Performing\Harmony\Components\Forms;

use Performing\Harmony\Components\Component;

class Form extends Component
{
    protected array $fields = [];

    protected array $data = [];

    protected string $action = '';

    public static function make()
    {
        return new static();
    }

    public function data($data)
    {
        $this->data = $data;

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

    public function toArray()
    {
        return [
            'fields' => $this->fields,
            'data' => $this->data,
            'action' => $this->action,
        ];
    }
}
