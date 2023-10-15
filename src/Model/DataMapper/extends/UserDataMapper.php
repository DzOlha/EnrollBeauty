<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\UserDataSource;

class UserDataMapper extends DataMapper
{
    public function __construct(UserDataSource $ds)
    {
        parent::__construct($ds);
    }
}