<?php

namespace Src\Model\Repository\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\impl\Repository;
use Src\Model\Table\Departments;
use Src\Model\Table\Services;

class ServiceRepository extends Repository
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
     * @param int $departmentId
     * @return array|false
     * [
     *      0 => [ id => , name => ]
     *      1 => [ id => , name => ]
     * ................................
     * ]
     */
    public function selectAllByDepartmentId(int $departmentId): array | false
    {
        $this->builder->select([Services::$id, Services::$name])
            ->from(Services::$table)
            ->whereEqual(Services::$department_id, ':department_id', $departmentId)
            ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false
     *
     * [
     *      0 => [ id =>, name =>, department_name =>, department_id => ]
     *      1 => [ id =>, name =>, department_name =>, department_id => ]
     * ....................................................................
     *      totalRowsCount =>
     * ]
     */
    public function selectAllLimited(
        int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    ): array | false
    {
        $queryFrom = "
            ".Services::$table." 
                INNER JOIN ".Departments::$table." ON ".Services::$department_id." = ".Departments::$id."
        ";

        $this->builder->select([Services::$id, Services::$name,
                                Departments::$name." as department_name",
                                Departments::$id." as department_id"])
            ->from(Services::$table)
            ->innerJoin(Departments::$table)
            ->on(Services::$department_id, Departments::$id)
            ->orderBy($orderByField, $orderDirection)
            ->limit($limit)
            ->offset($offset)
            ->build();

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result);
    }
}