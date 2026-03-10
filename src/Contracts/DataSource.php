<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @template T */
interface DataSource
{
    /** @param DataTable<T> $table */
    public function present(DataTable $table): ResourceCollection;
}
