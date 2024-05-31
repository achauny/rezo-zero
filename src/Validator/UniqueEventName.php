<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueEventName extends Constraint
{
    public string $message = 'Un événement avec ce nom "{{ value }}" existe déjà.';

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}