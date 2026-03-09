<?php

declare(strict_types=1);

namespace Performing\Harmony\Sources;

use Performing\Harmony\Contracts\FilterSource;
use Illuminate\Http\Request;

final readonly class RequestFilterSource implements FilterSource
{
    public function __construct(
        private Request $request,
    ) {}

    public function get(string $key): ?string
    {
        if ($this->request->has("filters.{$key}")) {
            $value = $this->request->input("filters.{$key}", '');

            if (!is_string($value)) {
                return null;
            }

            return $value;
        }

        return null;
    }
}
