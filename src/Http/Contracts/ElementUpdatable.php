<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Contracts;

use Illuminate\Http\RedirectResponse;
use Performing\Harmony\Http\Requests\UpdateRequest;

interface ElementUpdatable
{
    public function update(UpdateRequest $request, string $model): RedirectResponse;
}
