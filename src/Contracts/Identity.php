<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface Identity
{
    public string $uuid { get; }

    public string $name { get; }

    public string $handle { get; }

    public RenderType $type { get; }
}
