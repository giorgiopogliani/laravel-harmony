<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields;

use Override;
use Performing\Harmony\Contracts\Visibility;

final readonly class FieldVisibility implements Visibility
{
    public function __construct(private bool $hidden = false) {}

    #[Override]
    public function hidden(): bool
    {
        return $this->hidden;
    }
}
