<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;

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
