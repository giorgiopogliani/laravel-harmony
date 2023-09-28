<?php

namespace Performing\Harmony\Components\Tables;

use Closure;
use Illuminate\Support\Str;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasType;

class TableColumn extends Component
{
    use HasType;

    public ?Closure $format = null;

    public function __construct(string $title, ?string $key = null)
    {
        $this->data['title'] = $title;
        $this->data['key'] = $key ?? Str::of($title)->lower()->slug('_')->toString();
        $this->data['type'] = 'text';
    }

    public static function make(string $title, ?string $key = null)
    {
        return new static($title, $key);
    }

    public function sortable(): self
    {
        $this->data['sortable'] = true;

        return $this;
    }

    public function format(Closure $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function hidden(): self
    {
        $this->data['hidden'] = true;

        return $this;
    }
}
