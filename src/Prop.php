<?php

namespace Performing\Harmony;

#[\Attribute]
class Prop
{
    public function __construct(
        public ?string $key = null
    ) {
    }
}
