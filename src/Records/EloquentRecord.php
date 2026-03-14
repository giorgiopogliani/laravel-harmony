<?php

declare(strict_types=1);

namespace Performing\Harmony\Records;

use Illuminate\Database\Eloquent\Model;
use Override;
use Performing\Harmony\Contracts\Linkable;
use Performing\Harmony\Contracts\Record;

/**
 * @template T of Model
 *
 * @implements Record<T>
 */
final class EloquentRecord implements Record, Linkable
{
    /** @param T $model */
    public function __construct(private readonly Model $model) {}

    /** @return T */
    #[Override]
    public function model(): mixed
    {
        return $this->model;
    }

    #[Override]
    public function url(): string
    {
        return $this->model->url();
    }

    public function data(): array
    {
        return $this->model->toArray();
    }
}
