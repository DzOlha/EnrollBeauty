<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class PasswordHashValidator implements IValidator
{
    private string $actualPassword;

    /**
     * @param string $actualPassword
     */
    public function __construct(string $actualPassword)
    {
        $this->actualPassword = $actualPassword;
    }

    public function validate($value): bool
    {
        return password_verify($value, $this->actualPassword);
    }
}