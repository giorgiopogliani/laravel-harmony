<?php

namespace Performing\Harmony;

use Inertia\Response;

interface ResourceIndexable
{
    public function index(): Response;
}
