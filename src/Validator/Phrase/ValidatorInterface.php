<?php

namespace Matecat\AJV\Validator\Phrase;

interface ValidatorInterface
{
    public static function validate( array $phrase, int $index ): array;
}