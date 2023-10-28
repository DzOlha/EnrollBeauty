<?php

namespace Unit\src\Service\Validator\impl;

use PHPUnit\Framework\TestCase;
use Src\Service\Validator\impl\PasswordHashValidator;

class PasswordHashValidatorTest extends TestCase
{
    public function testValidPasswordHash()
    {
        $actualPassword = 'secret_password';
        $passwordHash = password_hash($actualPassword, PASSWORD_BCRYPT);

        $validator = new PasswordHashValidator($passwordHash);
        $isValid = $validator->validate($actualPassword);

        $this->assertTrue($isValid);
    }

    public function testInvalidPasswordHash()
    {
        $actualPassword = 'secret_password';
        $incorrectPassword = 'incorrect_password';
        $passwordHash = password_hash($actualPassword, PASSWORD_BCRYPT);

        $validator = new PasswordHashValidator($passwordHash);
        $isValid = $validator->validate($incorrectPassword);

        $this->assertFalse($isValid);
    }

    public function testEmptyPasswordHash()
    {
        $actualPassword = 'secret_password';
        $validator = new PasswordHashValidator('');

        $isValid = $validator->validate($actualPassword);

        $this->assertFalse($isValid);
    }
}
