<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;
use Performing\Harmony\Components\Forms\FormField;
use Performing\Harmony\Http\Requests\StoreRequest;
use Spatie\Flash\Message;

trait ElementStore
{
    public function store(StoreRequest $request): RedirectResponse
    {
        $rules = collect($this->element()->fields())
            ->mapWithKeys(fn (FormField $field) => [ $field->name => $field->rules ])
            ->toArray();

        $data = $request->validate($rules);

        $customer = $this->element()->query()->create($data);

        flash()->flashMessage(new Message(
            message: __('Created successfully.'),
            level: 'success',
        ));

        return redirect()->route($this->element()->handle() . '.index', $customer);
    }
}
