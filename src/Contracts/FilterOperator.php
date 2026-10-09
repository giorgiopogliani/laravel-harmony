<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Contracts\Validation\ValidationRule;

interface FilterOperator
{
    public function key(): string;

    public function label(): string;

    /** Input type (for example text, date, select, multiselect), or null for no input. */
    public function inputType(): ?string;

    /** @return array<array-key, mixed> */
    public function options(): array;

    /** @return list<string|ValidationRule> */
    public function rules(): array;

    public function default(): mixed;

    /** Apply the operator to the supplied database column or expression. */
    public function apply(Builder $query, string|Expression $column, mixed $value): Builder;
}
