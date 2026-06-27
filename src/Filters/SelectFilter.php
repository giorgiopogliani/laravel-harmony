<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters;

use Performing\Harmony\Contracts\Filter;
use Performing\Harmony\Contracts\FilterSource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Override;

final readonly class SelectFilter implements Filter
{
    public function __construct(
        private FilterSource $source,
        private string $column,
        private string $title,
        private array $options,
    ) {}

    #[Override]
    public function key(): string
    {
        return $this->column;
    }

    #[Override]
    public function label(): string
    {
        return $this->title;
    }

    #[Override]
    public function type(): string
    {
        return 'select';
    }

    #[Override]
    public function inline(): bool
    {
        return true;
    }

    #[Override]
    public function apply(Builder $query): Builder
    {
        $value = $this->source->get($this->key());

        if (is_null($value)) {
            return $query;
        }

        return $query->where($this->column, $value);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key(),
            'title' => $this->label(),
            'type' => $this->type(),
            'inline' => $this->inline(),
            'options' => $this->options,
            'value' => $this->source->get($this->key()),
            'encoding' => 'plain',
        ];
    }
}
