<?php

namespace Performing\Harmony\Components;

class HeaderComponent extends Component
{
    protected string $title;

    protected string $type = 'header';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function make(string $title): self
    {
        return new static($title);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
        ];
    }
}
