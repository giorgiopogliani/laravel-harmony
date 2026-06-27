<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields;

use Performing\Harmony\Contracts\Identity;
use Performing\Harmony\Contracts\RenderType;

final readonly class FieldIdentity implements Identity
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $handle,
        public RenderType $type,
    ) {}
}
