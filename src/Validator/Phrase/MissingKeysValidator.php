<?php

namespace Matecat\AJV\Validator\Phrase;

use Matecat\AJV\Enum\LevelEnum;

class MissingKeysValidator implements ValidatorInterface
{
    public static function validate(array $phrase, int $index): array
    {
        $keys = [
            "collection_description",
            "collection_name",
            "collection_product_spec_url",
            "created_at",
            "description",
            "id",
            "key",
            "product_spec_url",
            "segment_id",
            "service_type",
            "source_locale",
            "target_locales",
            "updated_at",
            "value"
        ];

        if (array_keys($phrase) !== $keys) {
            return [
                'node'       => $index,
                'id'         => $phrase['id'] ?? null,
                'key'        => $phrase['key'] ?? null,
                'segment_id' => $phrase['segment_id'] ?? null,
                'message'    => 'Missing keys: ' . implode(',', array_diff($keys, array_keys($phrase))),
                'level'      => LevelEnum::ERROR
            ];
        }

        return [];
    }
}
