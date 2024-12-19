<?php

declare(strict_types=1);

namespace Performing\Harmony;

#[\Attribute]
class Prop
{
    public function __construct(
        public ?string $key = null
    ) {
    }
}
