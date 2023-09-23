<?php

namespace Performing\Harmony\Http\Contracts;

use Inertia\Response;

interface ElementEditable
{
    public function edit(): Response;
}
