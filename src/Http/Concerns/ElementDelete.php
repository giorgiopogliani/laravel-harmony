<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;
use Spatie\Flash\Message;

trait ElementDelete
{
    public function destroy(): RedirectResponse
    {
        $id = request()->route('model');

        $this->element()->query()->find($id)->delete($id);

        flash(__('Deleted successfully.'))->success();

        return redirect()->route($this->element()->handle() . '.index');
    }
}
