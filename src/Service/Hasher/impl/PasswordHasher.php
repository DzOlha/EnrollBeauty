<?php

namespace Src\Service\Hasher\impl;

use Src\Service\Hasher\IHasher;

class PasswordHasher implements IHasher
{
    public static function hash($value): string
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
}