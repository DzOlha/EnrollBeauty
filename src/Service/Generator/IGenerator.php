<?php

namespace Src\Service\Generator;

interface IGenerator
{
    public function generate($pattern = null);
}