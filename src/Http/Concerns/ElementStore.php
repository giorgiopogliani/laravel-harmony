<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Http\Requests\StoreRequest;

trait ElementStore
{
    public function store(StoreRequest $request): RedirectResponse
    {
        $rules = collect($this->element()->fields())
            ->mapWithKeys(fn (FormField $field) => [ $field->name => $field->rules ])
            ->toArray();

        $data = $request->validate($rules);

        $customer = $this->element()->query()->create($data);

        flash(__('Created successfully.'))->success();

        return redirect()->route($this->element()->handle() . '.index', $customer);
    }
}
