<?php

declare(strict_types=1);

namespace Performing\Harmony\Components;

use Performing\Harmony\Concerns\IsConditional;

final class Chart implements Component
{
    use IsConditional;

    protected ?array $layout = null;

    /** @var Dataset[] */
    protected array $datasets = [];

    public function __construct() {}

    public static function make(): static
    {
        return new static();
    }

    public function title(string $title): static
    {
        $this->layout = [
            'title' => ['text' => $title],
        ];

        return $this;
    }

    public function datasets(array $datasets): static
    {
        $this->datasets = $datasets;

        return $this;
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'layout' => $this->layout,
            'dataset' => $this->datasets,
        ]);
    }
}
