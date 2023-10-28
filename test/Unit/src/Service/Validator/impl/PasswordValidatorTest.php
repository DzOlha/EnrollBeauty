<?php

namespace Unit\src\Service\Validator\impl;

use PHPUnit\Framework\TestCase;
use Src\Service\Validator\impl\PasswordValidator;

class PasswordValidatorTest extends TestCase {
    private $validator;

    protected function setUp(): void {
        $this->validator = new PasswordValidator();
    }

    /**
     * Valid
     */
    public function testValidPassword1() {
        $password = 'P@ssw0rd';
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    public function testValidPassword2() {
        $password = 'AnotherP@ssw0rd';
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    public function testValidPassword3() {
        $password = '1234abcdE@';
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    public function testValidPassword4() {
        $password = 'Secure#2022';
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    public function testValidPassword5() {
        $password = '3xAmP!3i';
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    public function testValidPassword6() {
        $password = 'P@ssP@ssP@ssP@s1s';
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    /**
     * Not Valid
     */
    public function testInvalidShort() {
        $password = 'P@sw0';
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }

    public function testInvalidNoUppercase() {
        $password = 'a2aaabb@bd#';
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }

    public function testInvalidNoLowercase() {
        $password = 'TE1234PASS1@';
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }

    public function testInvalidNoDigit() {
        $password = 'pass!worD';
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }

    public function testInvalidNoSpecialChars() {
        $password = 'PaSs5word';
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }

    public function testInvalidEmptyPassword() {
        $password = '';
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }

    public function testInvalidNullPassword() {
        $password = null;
        $result = $this->validator->validate($password);
        $this->assertFalse($result);
    }
}

