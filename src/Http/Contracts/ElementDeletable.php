<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Contracts;

use Illuminate\Http\RedirectResponse;

interface ElementDeletable
{
    public function destroy(): RedirectResponse;
}
