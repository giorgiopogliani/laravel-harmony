<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Values;

use Override;
use Performing\Harmony\Contracts\Value;

final readonly class WorkflowValue implements Value
{
    public function __construct(
        private mixed $id,
        private string $displayName = '',
        private ?string $color = null,
        private int $order = 0,
        private bool $isCompleted = false,
    ) {}

    #[Override]
    public function toString(): string
    {
        return $this->displayName;
    }

    #[Override]
    public function toStorage(): mixed
    {
        return $this->id;
    }

    #[Override]
    public function toContent(): mixed
    {
        return [
            'value' => $this->id,
            'label' => $this->displayName,
            'color' => $this->color,
            'order' => $this->order,
            'is_completed' => $this->isCompleted,
        ];
    }
}
