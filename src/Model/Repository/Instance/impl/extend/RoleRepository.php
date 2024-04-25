<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Roles;
use Src\Model\Table\Workers;

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

    /**
     * [ id =>, name => ]
     */
    public function selectByWorkerId(int $workerId): array | false
    {
        $this->builder->select([Roles::$id, Roles::$name])
            ->from(Roles::$table)
            ->innerJoin(Workers::$table)
                ->on(Roles::$id, Workers::$role_id)
            ->whereEqual(Workers::$id, ':id', $workerId)
        ->build();

        return $this->db->singleRow();
    }
}