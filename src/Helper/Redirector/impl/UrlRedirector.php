<?php

namespace Src\Helper\Redirector\impl;

use Src\Helper\Redirector\IRedirector;

class UrlRedirector implements IRedirector
{
    public static function redirect(string $to): void
    {
        header("Location: $to");
    }

}