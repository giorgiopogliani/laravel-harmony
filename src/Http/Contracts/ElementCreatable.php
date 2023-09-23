<?php

namespace Performing\Harmony\Http\Contracts;

use Inertia\Response;

interface ElementCreatable
{
    public function create(): Response;
}
