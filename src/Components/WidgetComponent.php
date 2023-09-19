<?php

namespace Performing\Harmony\Components;

class WidgetComponent extends Component
{
    protected $data = [
        'title' => '',
        'type' => '',
    ];

    public static function make(string $title)
    {
        return new static($title);
    }

    public function title(string $title): string
    {
        return $this->data['title'] = $title;
    }

    public function type(string $type): string
    {
        return $this->data['type'] = $type;
    }

    public function toArray()
    {
        return $this->data;
    }
}
