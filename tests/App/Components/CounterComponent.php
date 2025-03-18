<?php

declare(strict_types=1);

namespace Tests\App\Components;

use Performing\Harmony\RouteComponent;

class CounterComponent extends RouteComponent
{
    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
}
