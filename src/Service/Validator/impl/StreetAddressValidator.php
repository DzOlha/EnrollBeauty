<?php

namespace Src\Service\Validator\impl;

class StreetAddressValidator
{
    public function validate($value): bool
    {
        //$pattern = "/^(str|вул\.)\. ([A-Za-z\p{Cyrillic}іїІЇ\s]+,)? \d+[A-Za-z\p{Cyrillic}іїІЇ]?\b/";
        $pattern = '/^(?!-+$)(str\.\s[A-Za-z\s\d-]+,\s\d+|str\.\s[A-Za-z\s\d-]+,\s\d+-[A-Za-z])$/';
        if (preg_match($pattern, $value)) {
            return true;
        }
        return false;
    }
}