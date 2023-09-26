<?php

namespace Performing\Harmony\Http\Concerns;

use Illuminate\Http\RedirectResponse;
use Spatie\Flash\Message;

trait ElementDelete
{
    public function destroy(): RedirectResponse
    {
        $id = request()->route('model');

        $this->element()->query()->delete($id);

        flash()->flashMessage(new Message(
            message: __('Deleted successfully.'),
            level: 'success',
        ));

        return redirect()->route($this->element()->handle() . '.index');
    }
}
