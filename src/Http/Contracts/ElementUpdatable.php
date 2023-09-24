<?php

namespace Performing\Harmony\Http\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface ElementUpdatable
{
    public function update(Request $request, string $model): RedirectResponse;
}
