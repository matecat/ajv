<?php

namespace Matecat\AJV\Validator\Phrase;

class TargetValidator implements ValidatorInterface
{
    public static function validate(array $phrase, int $index): array
    {
        if ( isset($phrase['target_locales']) ) {

            $error = null;

            // is array??
            if (false === is_array($phrase['target_locales'])){
                $error = 'Not an array';
            }

            // has repeated keys?
            if (true === self::hasTargetDuplicates($phrase['target_locales'])) {
                $error = 'Repeated locale';
            }

            if (null !== $error) {
                return [
                    'node'       => $index,
                    'id'         => $phrase['id'] ?? null,
                    'key'        => $phrase['key'] ?? null,
                    'segment_id' => $phrase['segment_id'] ?? null,
                    'message'    => $error
                ];
            }
        }

        return [];
    }

    private static function hasTargetDuplicates(array $array = []): bool
    {
        $locales = [];

        for ($i = 0; $i < count($array); $i++) {
            $locales[] = $array[$i]['locale'];
        }

        return count($locales) !== count(array_unique($locales));
    }
}