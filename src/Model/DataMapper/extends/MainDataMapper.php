<?php

namespace Model\DataMapper\extends;

use DB\IDatabase;
use Model\DataMapper\DataMapper;
use Model\DataSource\DataSource;

class MainDataMapper extends DataMapper
{
    public function __construct(DataSource $ds)
    {
        parent::__construct($ds);
    }
}