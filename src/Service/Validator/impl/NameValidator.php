<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class NameValidator implements IValidator
{
    private int $minLength = 3;
    private int $maxLength = 50;

    private bool $allowWhitespace;

    public function __construct(
        $minLength = null, $maxLength = null, $allowWhitespace = false
    ){
        $this->minLength = $minLength ?? $this->minLength;
        $this->maxLength = $maxLength ?? $this->maxLength;
        $this->allowWhitespace = $allowWhitespace;
    }

    public function validate($value): bool
    {
        if ($this->allowWhitespace) {
            $pattern = "/^(?=.*\S)[A-Za-z\p{Cyrillic}іїІЇ\s]{{$this->minLength},{$this->maxLength}}$/u";
        } else {
            $pattern = "/^(?=.*\S)[A-Za-z\p{Cyrillic}іїІЇ]{{$this->minLength},{$this->maxLength}}$/u";
        }

        if (preg_match($pattern, $value)) {
            return true;
        }
        return false;
    }

    public function setMaxLength(int $value): void
    {
        $this->maxLength = $value;
    }
}