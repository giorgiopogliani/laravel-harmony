<?php

namespace Performing\Harmony\Http\Contracts;

use Illuminate\Http\RedirectResponse;
use Request;

interface ElementDeletable
{
    public function delete(): RedirectResponse;
}
