<?php

namespace Performing\Harmony\Components\Table;

use Closure;
use Illuminate\Support\Str;
use Performing\Harmony\Components\Component;

class TableColumn extends Component
{
    protected array $data = ['type' => 'text'];

    public ?Closure $format = null;

    public function __construct(string $title, ?string $key = null)
    {
        $this->data['title'] = $title;
        $this->data['key'] = $key ?? Str::of($title)->lower()->slug('_')->toString();
    }

    public static function make(string $title, ?string $key = null)
    {
        return new static($title, $key);
    }

    public function sortable()
    {
        $this->data['sortable'] = true;

        return $this;
    }

    public function format(Closure $format)
    {
        $this->format = $format;

        return $this;
    }

    public function component(string $type)
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function hidden()
    {
        $this->data['hidden'] = true;

        return $this;
    }

    public function toArray()
    {
        return $this->data;
    }
}
