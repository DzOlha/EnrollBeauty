<?php

namespace Src\Model\DataMapper\extends;

use Src\DB\IDatabase;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\DataSource;

class MainDataMapper extends DataMapper
{
    public function __construct(DataSource $ds)
    {
        parent::__construct($ds);
    }
}