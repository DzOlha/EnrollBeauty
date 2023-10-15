<?php

namespace Src\Service\Auth;

use Src\Model\DataMapper\DataMapper;

abstract class AuthService
{
    protected DataMapper $dataMapper;

    /**
     * @param DataMapper $dataMapper
     */
    public function __construct(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }
}