<?php

namespace Unit\src\Service\Validator\impl;

use PHPUnit\Framework\TestCase;
use Src\Service\Validator\impl\NameValidator;

class NameValidatorTest extends TestCase {
    private $validator;

    protected function setUp(): void {
        $this->validator = new NameValidator();
    }

    public function testValidName() {
        $name = 'John';
        $result = $this->validator->validate($name);
        $this->assertTrue($result);
    }

    public function testValidNameWithUnicodeCharacters() {
        $name = 'Петро';
        $result = $this->validator->validate($name);
        $this->assertTrue($result);
    }

    public function testInvalidName() {
        $name = 'Jo';
        $result = $this->validator->validate($name);
        $this->assertFalse($result);
    }

    public function testInvalidNameWithNumbers() {
        $name = 'John123';
        $result = $this->validator->validate($name);
        $this->assertFalse($result);
    }

    public function testInvalidNameWithSpecialChars() {
        $name = 'John %4';
        $result = $this->validator->validate($name);
        $this->assertFalse($result);
    }

    public function testEmptyName() {
        $name = '';
        $result = $this->validator->validate($name);
        $this->assertFalse($result);
    }

    public function testNullName() {
        $name = null;
        $result = $this->validator->validate($name);
        $this->assertFalse($result);
    }
}
