<?php

namespace Src\Helper\Redirector;

interface IRedirector
{
    public static function redirect(string $to);
}