<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Closure;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @template T
 * @template B
 */
interface DataSource
{
    /** @var Closure(T): B */
    public Closure $record { get; }

    /** @return array<string, mixed> */
    public function additional(): array;

    /** @param DataTable<T> $table */
    public function present(DataTable $table): ResourceCollection;
}
