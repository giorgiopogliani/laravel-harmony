<?php

namespace Performing\Harmony;

use Inertia\Response;

interface ResourceEditable
{
    public function create(): Response;

    public function edit(): Response;

    public function form(): ResourceData;
}
