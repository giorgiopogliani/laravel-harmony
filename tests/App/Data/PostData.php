<?php

namespace Tests\App\Data;

use Performing\Harmony\Data;

class PostData extends Data
{
    public function __construct(
        public string $title,
        public string $body,
    ) {}
}
