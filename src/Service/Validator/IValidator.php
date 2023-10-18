<?php

namespace Src\Service\Validator;

interface IValidator
{
    public function validate($value): bool;
}