<?php

declare(strict_types=1);

namespace Performing\Harmony\Concerns;

trait HasTitle
{
    public function __construct(?string $title = null)
    {
        $this->data['title'] = $title;
        
        parent::__construct();
    }

    public static function make(?string $title = null)
    {
        return new static($title);
    }

    public function getTitle(): string
    {
        return $this->data['title'];
    }
}
