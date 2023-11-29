<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class RecoveryCodeValidator implements IValidator
{
    public function validate($value): bool
    {
        $pattern = "/^[0-9a-f]{64}$/"; // Matches 64 hexadecimal characters
        if(preg_match($pattern, $value)) {
            return true;
        }
        return false;
    }

}