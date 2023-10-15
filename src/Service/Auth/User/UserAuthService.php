<?php

namespace Src\Service\Auth\User;

use Src\Model\DataMapper\DataMapper;
use Src\Service\Auth\AuthService;

class UserAuthService extends AuthService
{
    /**
     * @param DataMapper $dataMapper
     */
    public function __construct(DataMapper $dataMapper)
    {
        parent::__construct($dataMapper);
    }

}