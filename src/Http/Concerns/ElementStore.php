<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Performing\Harmony\Components\Form\Input;

trait ElementStore
{
    public function store(Request $request): RedirectResponse
    {
        $rules = collect($this->element()->fields())
            ->mapWithKeys(fn (Input $field) => [ $field->name => $field->validation ])
            ->toArray();

        $data = $this->validate($request, $rules);

        $customer = $this->query()->create($data);

        flash()->success(__('Created successfully.'));

        return redirect()->route($this->element()->handle() . '.index', $customer);
    }
}
