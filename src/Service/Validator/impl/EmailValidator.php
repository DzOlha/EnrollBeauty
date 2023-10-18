<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class EmailValidator implements IValidator
{
    public function validate($value): bool
    {
        if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/", $value)) {
            return true;
        }
        return false;
    }

}