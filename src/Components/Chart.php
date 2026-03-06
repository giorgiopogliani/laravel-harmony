<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasMake;
use Performing\Harmony\Concerns\IsComponent;

class Chart implements Component
{
    use IsComponent;
    use HasMake;

    public function title(string $title)
    {
        $this->data['layout'] = [
            'title' => ['text' => $title]
        ];

        return $this;
    }

    public function datasets($array)
    {
        $this->data['dataset'] = $array;

        return $this;
    }
}
