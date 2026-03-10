<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Tables;

use Closure;
use Illuminate\Support\Str;
use JsonSerializable;
use Performing\Harmony\Components\Component;
use Performing\Harmony\Concerns\HasKey;
use Performing\Harmony\Concerns\HasType;
use Performing\Harmony\Contracts\Column;
use Performing\Harmony\Contracts\Record;
use Performing\Harmony\Contracts\RenderType;
use Performing\Harmony\RenderTypes\TextRenderType;

class TableColumn extends Component implements Column, JsonSerializable
{
    use HasType;
    use HasKey;

    public ?Closure $format = null;

    public function __construct(string $title, ?string $key = null)
    {
        $this->data['type'] = 'text';
        parent::__construct();

        $this->data['title'] = $title;
        $this->data['key'] = $key ?? Str::of($title)
            ->lower()
            ->slug('_')
            ->toString();
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

    public function key(): string
    {
        return $this->getKey();
    }

    public function label(): string
    {
        return $this->data['title'];
    }

    public function value(Record $record): mixed
    {
        if ($this->format instanceof Closure) {
            $value = call_user_func($this->format, $record->model(), $this);

            if ($value instanceof \Illuminate\Contracts\Support\Arrayable || is_array($value)) {
                return collect($value)->toArray();
            }

            return $value;
        }

        return data_get($record->model(), $this->key());
    }

    public function type(): RenderType
    {
        return new TextRenderType;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
