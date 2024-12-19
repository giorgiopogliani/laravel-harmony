<?php

declare(strict_types=1);

namespace Performing\Harmony\Http\Contracts;

use Inertia\Response;

interface ElementEditable
{
    public function edit(): Response;
}
