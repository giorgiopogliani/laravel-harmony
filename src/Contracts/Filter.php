<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use JsonSerializable;

interface Filter extends JsonSerializable
{
    public function key(): string;

    public function label(): string;

    public function type(): string;

    public function inline(): bool;

    public function apply(Builder $query): Builder;
}
