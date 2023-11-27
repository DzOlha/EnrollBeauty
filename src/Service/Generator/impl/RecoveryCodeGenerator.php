<?php

namespace Src\Service\Generator\impl;

use Src\Service\Generator\IGenerator;

class RecoveryCodeGenerator implements IGenerator
{
    public function generate($pattern = null)
    {
        /**
         * the final length is 64 characters
         */
       return bin2hex(random_bytes(32));
    }

}