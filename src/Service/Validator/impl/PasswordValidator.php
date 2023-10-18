<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class PasswordValidator implements IValidator
{
    public function validate($value): bool
    {
        if (preg_match(
            '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,30}$/',
                    $value)
        ) {
            return true;
        }
        return false;
    }

}