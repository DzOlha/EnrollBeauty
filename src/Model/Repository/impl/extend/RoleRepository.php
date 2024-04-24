<?php

namespace Src\Model\Repository\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\impl\Repository;
use Src\Model\Table\Roles;

class RoleRepository extends Repository
{
    protected static ?Repository $instance = null;

    public static function getInstance(
        IDatabase $db = null, SqlBuilder $builder = null
    ){
        if (self::$instance === null) {
            self::$instance = new self($db, $builder);
        }
        return self::$instance;
    }

    /**
     * @return array|false
     * [ id => , name => ]
     */
    public function selectAll(): array | false
    {
        $this->builder->select([Roles::$id, Roles::$name])
            ->from(Roles::$table)
            ->build();

        return $this->db->manyRows();
    }
}