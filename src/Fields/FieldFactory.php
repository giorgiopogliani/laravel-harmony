<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields;

use Performing\Harmony\Contracts\Field;

final readonly class FieldFactory
{
    public function __construct(private FieldRegistry $registry) {}

    public function make(string $type, array $data): Field
    {
        $class = $this->registry->classFor($type);

        $identity = new FieldIdentity(
            uuid: $data['uuid'],
            name: $data['name'] ?? 'No name',
            handle: $data['handle'],
            type: $this->registry->renderTypeFor($type),
        );

        $config = $data['config'] ?? [];

        $validation = new FieldValidation($config['rules'] ?? []);
        $visibility = new FieldVisibility($config['hidden'] ?? false);

        return app($class, [
            'identity' => $identity,
            'validation' => $validation,
            'visibility' => $visibility,
            ...collect($config)->except(['hidden', 'rules'])->all(),
        ]);
    }
}
