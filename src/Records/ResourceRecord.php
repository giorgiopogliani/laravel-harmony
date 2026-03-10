<?php

declare(strict_types=1);

namespace Performing\Harmony\Records;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Performing\Harmony\Contracts\Record;
use Spatie\LaravelData\Data;

/**
 * @template T of Model
 *
 * @implements Record<T>
 */
final class ResourceRecord implements Record
{
    private readonly array $data;

    /**
     * @param  T  $model
     * @param  class-string<JsonResource|Data>  $resource
     */
    public function __construct(
        private readonly Model $model,
        string $resource,
    ) {
        $this->data = match (true) {
            is_a($resource, JsonResource::class, true) => $resource::make($model)->resolve(),
            is_a($resource, Data::class, true) => $resource::from($model)->toArray(),
            default => $model->toArray(),
        };
    }

    /** @return T */
    public function model(): mixed
    {
        return $this->model;
    }

    public function data(): array
    {
        return $this->data;
    }
}
