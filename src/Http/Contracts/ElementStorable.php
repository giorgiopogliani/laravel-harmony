<?php

namespace Performing\Harmony\Http\Contracts;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface ElementStorable
{
    public function store(Request $request): RedirectResponse;
}
