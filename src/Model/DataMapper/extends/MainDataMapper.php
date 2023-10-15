<?php

namespace Src\Model\DataMapper\extends;

use Src\DB\IDatabase;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\DataSource;
use Src\Model\DataSource\extends\MainDataSource;

class MainDataMapper extends DataMapper
{
    public function __construct(MainDataSource $ds)
    {
        parent::__construct($ds);
    }
}