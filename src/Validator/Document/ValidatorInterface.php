<?php

namespace Matecat\AJV\Validator\Document;

interface ValidatorInterface
{
    public static function validate( array $phrases ): array;
}