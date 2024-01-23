<?php

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\HasMake;

class Chart extends Component
{
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
