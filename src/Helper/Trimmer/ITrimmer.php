<?php

namespace Src\Helper\Trimmer;

interface ITrimmer
{
    public function in(string $value);
    public function out(string $value);
}