<?php

namespace Performing\Harmony\Http\Contracts;

use Inertia\Response;

interface ElementShowable
{
    public function show(): Response;
}
