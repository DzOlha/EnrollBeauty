<?php

namespace Src\Model\Repository;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;

interface IRepository
{
    public static function getInstance(IDatabase $db, SqlBuilder $builder);
}