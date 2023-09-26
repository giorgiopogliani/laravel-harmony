<?php

namespace Performing\Harmony\Concerns;

trait HasTitle
{
    public function __construct(string $title)
    {
        $this->data['title'] = $title;
    }

    public static function make(string $title)
    {
        return new static($title);
    }

    public function getTitle(): string
    {
        return $this->data['title'];
    }
}
