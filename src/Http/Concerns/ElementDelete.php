<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;

trait ElementDelete
{
    public function delete(): RedirectResponse
    {
        $id = request()->route('model');

        $this->element()->query()->delete($id);

        flash()->success(__('Delete successfully.'));

        return redirect()->route($this->element()->handle() . '.index');
    }
}
