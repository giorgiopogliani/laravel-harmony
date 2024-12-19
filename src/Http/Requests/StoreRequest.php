<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return collect(app('element')->fields())
            ->mapWithKeys(fn ($field) => [$field->name => $field->rules])
            ->toArray();
    }
}
