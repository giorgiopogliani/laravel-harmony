<?php

declare(strict_types=1);

namespace Performing\Harmony\Contracts;

interface View
{
    /**
     * Type of the view
     */
    public function type(): string;

    /**
     * Array with the valid column handles to show
     *
     * @return array<string>
     */
    public function columns(): array;

    /**
     * Configure the column to use for group by
     */
    public function grouped(): ?string;

    /** @return array<string, string> */
    public function filters(): array;
}
