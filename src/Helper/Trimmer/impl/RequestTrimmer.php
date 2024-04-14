<?php

namespace Src\Helper\Trimmer\impl;

use Src\Helper\Trimmer\ITrimmer;

class RequestTrimmer implements ITrimmer
{
    public function in(string $value)
    {
        return htmlspecialchars(trim($value));
    }

    public function out(string $value)
    {
        return html_entity_decode($value);
    }

}