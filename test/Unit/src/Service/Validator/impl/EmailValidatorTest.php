<?php

namespace Unit\src\Service\Validator\impl;

use PHPUnit\Framework\TestCase;
use Src\Service\Validator\impl\EmailValidator;

class EmailValidatorTest extends TestCase {
    private $validator;

    protected function setUp(): void {
        $this->validator = new EmailValidator();
    }

    public function testValidEmail() {
        $email = 'test@example.com';
        $result = $this->validator->validate($email);
        $this->assertTrue($result);
    }

    public function testInvalidEmail() {
        $email = 'invalid-email';
        $result = $this->validator->validate($email);
        $this->assertFalse($result);
    }

    public function testValidEmailWithUpperCaseCharacters() {
        $email = 'Test.User@Example.com';
        $result = $this->validator->validate($email);
        $this->assertTrue($result);
    }

    public function testInvalidEmailWithSpecialCharacters() {
        $email = 'test@example.com#';
        $result = $this->validator->validate($email);
        $this->assertFalse($result);
    }

    public function testEmptyEmail() {
        $email = '';
        $result = $this->validator->validate($email);
        $this->assertFalse($result);
    }

    public function testNullEmail() {
        $email = null;
        $result = $this->validator->validate($email);
        $this->assertFalse($result);
    }
}
