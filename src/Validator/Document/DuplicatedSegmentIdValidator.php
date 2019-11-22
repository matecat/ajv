<?php

namespace Matecat\AJV\Validator\Document;

class DuplicatedSegmentIdValidator implements ValidatorInterface
{
    public static function validate( array $phrases ): array
    {
        $segmentIds = [];
        $errors = [];

        foreach ($phrases as $phrase){
            $segmentIds[] = $phrase['segment_id'] ?? null;
        }

        foreach (array_unique($segmentIds) as $index => $segmentId) {
            $matches = array_filter($segmentIds, function ($value) use ($segmentId) {
                return $segmentId === $value;
            });

            $matchCounts = count($matches);

            if ($matchCounts > 1) {
                $phrase = $phrases[$index];
                $errors[] = [
                        'node'       => $index,
                        'id'         => $phrase['id'] ?? null,
                        'key'        => $phrase['key'] ?? null,
                        'segment_id' => $phrase['segment_id'] ?? null,
                        'message'    => 'segment_id duplicated'
                ];
            }
        }

        return $errors;
    }
}