<?php

namespace Matecat\AJV\Validator\Document;

class DuplicatedKeyValidator implements ValidatorInterface
{
    public static function validate(array $phrases): array
    {
        $segmentKeys = [];
        $errors = [];

        foreach ($phrases as $phrase) {
            $segmentKeys[] = $phrase['key'] ?? null;
        }

        foreach (array_unique($segmentKeys) as $index => $segmentKey) {
            $matches = array_filter($segmentKeys, function ($value) use ($segmentKey) {
                return $segmentKey === $value;
            });

            $matchCounts = count($matches);

            if ($matchCounts > 1) {
                $phrase = $phrases[$index];
                $errors[] = [
                        'node'       => $index,
                        'id'         => $phrase['id'] ?? null,
                        'key'        => $phrase['key'] ?? null,
                        'segment_id' => $phrase['segment_id'] ?? null,
                        'message'    => 'key duplicated'
                ];
            }
        }

        return $errors;
    }
}
