<?php

namespace Matecat\AJV\Validator\Document;

class DuplicateIdValidator implements ValidatorInterface
{
    public static function validate(array $phrases): array
    {
        $ids = [];
        $errors = [];

        foreach ($phrases as $phrase){
            $ids[] = $phrase['id'] ?? null;
        }

        foreach (array_unique($ids) as $index => $id) {
            $matches = array_filter($ids, function ($value) use ($id) {
                return $id === $value;
            });

            $matchCounts = count($matches);

            if ($matchCounts > 1) {
                $phrase = $phrases[$index];
                $errors[] = [
                    'node'       => $index,
                    'id'         => $phrase['id'] ?? null,
                    'key'        => $phrase['key'] ?? null,
                    'segment_id' => $phrase['segment_id'] ?? null,
                    'message'    => 'id duplicated'
                ];
            }
        }

        return $errors;
    }
}
