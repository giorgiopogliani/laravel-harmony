<?php

declare(strict_types=1);

namespace Performing\Harmony\Sources;

use Performing\Harmony\Contracts\FilterSource;

final readonly class SavedFilterSource implements FilterSource
{
    /** @param array<string, string> $filters */
    public function __construct(
        private array $filters,
    ) {}

    #[\Override]
    public function get(string $key): ?string
    {
        return $this->filters[$key] ?? null;
    }
}
