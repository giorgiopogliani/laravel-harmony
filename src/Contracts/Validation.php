<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

use Illuminate\Contracts\Validation\ValidationRule;

interface Validation
{
    /**
     * Get the validation rules for this field.
     *
     * @return array<string|ValidationRule>
     */
    public function rules(): array;
}
