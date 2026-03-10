<?php

declare(strict_types=1);

namespace Performing\Harmony\Records;

use Illuminate\Database\Eloquent\Model;
use Performing\Harmony\Contracts\Record;

/**
 * @template T of Model
 *
 * @implements Record<T>
 */
final class EloquentRecord implements Record
{
    /** @param T $model */
    public function __construct(private readonly Model $model) {}

    /** @return T */
    public function model(): mixed
    {
        return $this->model;
    }
}
