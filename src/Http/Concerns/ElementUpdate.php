<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Performing\Harmony\Components\Form\Input;
use Spatie\Flash\Message;

trait ElementUpdate
{
    public function update(Request $request, string $model): RedirectResponse
    {
        $model = $this->element()->query()->findOrFail($model);

        $rules = collect($this->element()->fields())
            ->mapWithKeys(fn (Input $field) => [ $field->name => $field->validation ])
            ->toArray();

        $data = $request->validate($rules);

        $model->update($data);

        flash()->flashMessage(new Message(
            message: __('Updated successfully.'),
            level: 'success',
        ));

        return redirect()->route($this->element()->handle() . '.index');
    }
}
