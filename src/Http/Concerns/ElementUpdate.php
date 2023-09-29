<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Http\Requests\UpdateRequest;
use Spatie\Flash\Message;

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

        flash('Updated successfully.')->success();

        return redirect()->route($this->element()->handle() . '.index');
    }
}
