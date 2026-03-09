<?php

declare(strict_types=1);

namespace Performing\Harmony\Sources;

use Performing\Harmony\Contracts\FilterSource;

final readonly class CompositeFilterSource implements FilterSource
{
    /** @var FilterSource[] */
    private array $sources;

    public function __construct(FilterSource ...$sources)
    {
        $this->sources = $sources;
    }

    public function get(string $key): ?string
    {
        foreach ($this->sources as $source) {
            $value = $source->get($key);

            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }
}
