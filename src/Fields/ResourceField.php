<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields;

use Illuminate\Contracts\Support\Arrayable;
use Override;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\HasOptions;

final readonly class ResourceField implements Arrayable
{
    public function __construct(private Field $field) {}

    #[Override]
    public function toArray(): array
    {
        $value = $this->field->getValue();

        $array = [
            'uuid' => $this->field->identity->uuid,
            'name' => $this->field->identity->name,
            'handle' => $this->field->identity->handle,
            'type' => $this->field
                ->identity
                ->type
                ->value(),
            'hidden' => $this->field->visibility->hidden(),
            'value' => $value?->toContent(),
            'rawValue' => $value?->toStorage(),
            'displayValue' => $value?->toString() ?? '',
        ];

        if ($this->field instanceof HasOptions) {
            $array['options'] = $this->field->getOptions();
        }

        return $array;
    }
}
