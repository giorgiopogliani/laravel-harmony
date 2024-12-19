<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Http\Requests\UpdateRequest;

trait ElementUpdate
{
    public function update(UpdateRequest $request, string $model): RedirectResponse
    {
        $model = $this->element()->query()->findOrFail($model);

        $rules = collect($this->element()->fields())
            ->mapWithKeys(fn (FormField $field) => [ $field->name => $field->rules ])
            ->toArray();

        $data = $request->validate($rules);

        $model->update($data);

        flash(__('Updated successfully.'))->success();

        return redirect()->route($this->element()->handle() . '.index');
    }
}
