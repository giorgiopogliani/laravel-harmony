<?php

declare(strict_types=1);

namespace Performing\Harmony\Filters\Operators;

use Illuminate\Contracts\Validation\ValidationRule;
use Performing\Harmony\Contracts\FilterOperator;

abstract class AbstractFilterOperator implements FilterOperator
{
    /**
     * @param  array<array-key, mixed>  $options
     * @param  list<string|ValidationRule>  $rules
     */
    public function __construct(
        private readonly ?string $inputType = 'text',
        private readonly array $options = [],
        private readonly array $rules = ['required'],
        private readonly mixed $defaultValue = null,
    ) {}

    public function inputType(): ?string
    {
        return $this->inputType;
    }

    public function options(): array
    {
        return $this->options;
    }

    public function rules(): array
    {
        return $this->rules;
    }

    public function default(): mixed
    {
        return $this->defaultValue;
    }
}
