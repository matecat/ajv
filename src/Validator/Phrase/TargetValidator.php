<?php

namespace Matecat\AJV\Validator\Phrase;

use Matecat\AJV\Enum\LevelEnum;

class TargetValidator implements ValidatorInterface
{
    public static function validate(array $phrase, int $index): array
    {
        if (isset($phrase['target_locales'])) {
            $error = null;

            // is array??
            if (false === is_array($phrase['target_locales'])) {
                $error = 'Not an array';
            }

            // has repeated keys?
            if (true === self::hasTargetDuplicates($phrase['target_locales'])) {
                $error = 'Repeated locale';
            }

            // are valid target(s)?
            if (count($a = self::areValidTargets($phrase['target_locales'])) > 0) {
                $error = implode(',', $a);
            }

            if (null !== $error) {
                return [
                    'node'       => $index,
                    'id'         => $phrase['id'] ?? null,
                    'key'        => $phrase['key'] ?? null,
                    'segment_id' => $phrase['segment_id'] ?? null,
                    'message'    => $error,
                    'level'      => LevelEnum::ERROR
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

    private static function areValidTargets(array $array = []): array
    {
        $errors = [];
        $validTargets = include __DIR__ . '/../../../config/target.php';

        foreach ($array as $item) {
            if (false === in_array($item['locale'], array_keys($validTargets))) {
                $errors[] = $item['locale'] . ' is not a valid target';
                break;
            }
        }

        return $errors;
    }
}
