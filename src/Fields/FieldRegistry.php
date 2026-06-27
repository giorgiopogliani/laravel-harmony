<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields;

use InvalidArgumentException;
use Performing\Harmony\Contracts\RenderType;

final class FieldRegistry
{
    /** @var array<string, class-string> */
    private array $types = [];

    /** @var array<string, RenderType> */
    private array $renderTypes = [];

    public function register(string $fieldType, RenderType $renderType, string $class): void
    {
        $this->types[$fieldType] = $class;
        $this->renderTypes[$fieldType] = $renderType;
    }

    /**
     * @return class-string
     *
     * @throws InvalidArgumentException if the field type is unknown
     */
    public function classFor(string $type): string
    {
        return $this->types[$type] ?? throw new InvalidArgumentException("Unknown field type: {$type}");
    }

    /**
     * @throws InvalidArgumentException if the field type is unknown
     */
    public function renderTypeFor(string $type): RenderType
    {
        return $this->renderTypes[$type] ?? throw new InvalidArgumentException("Unknown field type: {$type}");
    }

    /** @return string[] */
    public function types(): array
    {
        return array_keys($this->types);
    }

    /** @return array<string, class-string> */
    public function all(): array
    {
        return $this->types;
    }
}
