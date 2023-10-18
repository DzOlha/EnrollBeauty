<?php

namespace Src\Service\Hasher;

interface IHasher
{
    public static function hash($value);
}