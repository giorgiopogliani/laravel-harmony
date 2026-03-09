<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields;

use Illuminate\Contracts\Validation\ValidationRule;
use Override;
use Performing\Harmony\Contracts\Validation;

final readonly class FieldValidation implements Validation
{
    /**
     * @param  array<string|ValidationRule>  $rules
     */
    public function __construct(private array $rules = []) {}

    #[Override]
    public function rules(): array
    {
        return $this->rules;
    }
}
