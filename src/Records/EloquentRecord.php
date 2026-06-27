<?php

declare(strict_types=1);

namespace Performing\Harmony\Records;

use Illuminate\Database\Eloquent\Model;
use Performing\Harmony\Contracts\Linkable;

/**
 * @template T of Model
 */
final class EloquentRecord implements Linkable
{
    /** @param T $model */
    public function __construct(private readonly Model $model) {}

    /** @return T */
    public function model(): mixed
    {
        return $this->model;
    }

    public function url(): string
    {
        return $this->model->url();
    }

    public function data(): array
    {
        return $this->model->toArray();
    }
}
