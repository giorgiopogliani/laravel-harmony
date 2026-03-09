<?php

declare(strict_types=1);

namespace Performing\Harmony\Fields\Concerns;

use Illuminate\Support\Facades\Log;
use Performing\Harmony\Contracts\Field;
use Performing\Harmony\Contracts\Value;

/**
 * @mixin \Performing\Harmony\Contracts\Contentable
 * @mixin \Illuminate\Database\Eloquent\Model
 *
 * @require-extends \Illuminate\Database\Eloquent\Model
 *
 * @require-implements \Performing\Harmony\Contracts\Contentable
 */
trait ContentableTrait
{
    public function setField(Field $field, mixed $value): void
    {
        $value = $field->setValue($value)->getValue()?->toStorage();
        $uuid = $field->identity->uuid;
        $this->content = array_merge($this->content ?? [], [$uuid => $value]);
    }

    public function getFieldValue(Field $field): ?Value
    {
        $raw = $this->content[$field->identity->uuid] ?? $this->content[$field->identity->handle] ?? null;

        return $field->setValue($raw)->getValue();
    }

    public function updateFields(array $data): void
    {
        if (! $this->getLayout()) {
            Log::warning('Trying to update fields on a contentable without a layout', [
                'contentable' => $this::class,
            ]);

            return;
        }

        $this
            ->getLayout()
            ->getFields()
            ->each(function (Field $field) use ($data): void {
                $key = $this->getFieldKey($field, $data);
                if ($key !== null) {
                    $this->setField($field, $data[$key] ?? null);
                }
            });

        $this->save();
    }

    private function getFieldKey(Field $field, array $data): ?string
    {
        return
            array_key_exists($field->identity->uuid, $data)
                ? $field->identity->uuid
                : (array_key_exists($field->identity->handle, $data) ? $field->identity->handle : null);
    }
}
