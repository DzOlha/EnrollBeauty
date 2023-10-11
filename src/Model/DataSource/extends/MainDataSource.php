<?php

namespace Model\DataSource\extends;

use DB\IDatabase;
use Model\DataSource\DataSource;

class MainDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }
}