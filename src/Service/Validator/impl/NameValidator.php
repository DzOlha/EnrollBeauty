<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class NameValidator implements IValidator
{
    public function validate($value): bool
    {
        if (preg_match("/^[A-Za-z\p{Cyrillic}іїІЇ]{3,}$/u", $value)) {
            return true;
        }
        return false;
    }

}