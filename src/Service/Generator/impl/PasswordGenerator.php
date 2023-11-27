<?php

namespace Src\Service\Generator\impl;

use Src\Service\Generator\IGenerator;

class PasswordGenerator implements IGenerator
{
    private string $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,30}$/';
    private int $minLength = 8;
    private int $maxLength = 30;


    public function generate($pattern = null)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#@$!%*?&';
        $randomLength = rand($this->minLength, $this->maxLength);
        $password = '';

        for ($i = 0; $i < $randomLength; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $password;
    }
}