<?php

namespace Performing\Harmony\Http\Contracts;

use Illuminate\Http\RedirectResponse;
use Performing\Harmony\Http\Requests\StoreRequest;

interface ElementStorable
{
    public function store(StoreRequest $request): RedirectResponse;
}
