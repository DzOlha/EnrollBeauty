<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class NameValidator implements IValidator
{
    public function validate($value): bool
    {
        if (preg_match("/^[A-Za-zА-Яа-яіїІЇ]{3,}$/", $value)) {
            return true;
        }
        return false;
    }

}