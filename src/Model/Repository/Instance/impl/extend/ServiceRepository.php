<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Departments;
use Src\Model\Table\Positions;
use Src\Model\Table\Services;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;

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

    /**
     * @param int $workerId
     * @return array|false
     * [
     *       0 => [
     *           'id' => ,
     *           'name' => ,
     *       ]
     *  .............................
     *  ]
     */
    public function selectAllByWorkerId(int $workerId): array | false
    {
        $this->builder->select([Services::$id, Services::$name])
            ->from(WorkersServicePricing::$table)
            ->innerJoin(Services::$table)
                ->on(WorkersServicePricing::$service_id, Services::$id)
            ->whereEqual(WorkersServicePricing::$worker_id, ":id", $workerId)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @return array|false
     * [
     *        0 => [
     *            'id' => ,
     *            'name' => ,
     *        ]
     *   .............................
     *   ]
     */
    public function selectAll(): array | false
    {
        $this->builder->select([Services::$id, Services::$name])
            ->from(Services::$table)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * [
     *       0 => [ id =>, name =>, department_name =>, department_id => ]
     *       1 => [ id =>, name =>, department_name =>, department_id => ]
     *  ....................................................................
     *       totalRowsCount =>
     *  ]
     */
    public function selectAllLimitedByWorkerId(
        int $workerId, int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    ): array | false
    {
        $queryFrom = "
            ".Services::$table." 
                INNER JOIN ".Departments::$table." ON ".Services::$department_id." = ".Departments::$id."
                INNER JOIN ".Positions::$table." ON ".Departments::$id." = ".Positions::$department_id."
                INNER JOIN ".Workers::$table." ON ".Positions::$id." = ".Workers::$position_id."
            WHERE ".Workers::$id." = :worker_id
        ";
        $params = [
            ':worker_id' => $workerId
        ];

        $this->builder->select([Services::$id, Services::$name,
                                Departments::$name." as department_name",
                                Departments::$id." as department_id"])
            ->from(Services::$table)
            ->innerJoin(Departments::$table)
                ->on(Services::$department_id, Departments::$id)
            ->innerJoin(Positions::$table)
                ->on(Departments::$id, Positions::$department_id)
            ->innerJoin(Workers::$table)
                ->on(Positions::$id, Workers::$position_id)
            ->whereEqual(Workers::$id, ':id', $workerId)
            ->orderBy($orderByField, $orderDirection)
            ->limit($limit)
            ->offset($offset)
        ->build();

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result, $params);
    }

    public function selectIdByNameAndDepartment(
        string $serviceName, int $departmentId
    ): int | false
    {
        $this->builder->select([Services::$id])
            ->from(Services::$table)
            ->whereEqual(Services::$name, ':name', $serviceName)
            ->andEqual(Services::$department_id, ':department_id', $departmentId)
        ->build();

        $result = $this->db->singleRow();
        if($result) {
            // services.id -> id
            return $result[explode('.', Services::$id)[1]];
        }
        return $result;
    }

    public function insert(
        string $serviceName, int $departmentId
    ): int | false
    {
        $this->builder->insertInto(Services::$table, [Services::$name, Services::$department_id])
            ->values([':name', ":department_id"], [$serviceName, $departmentId])
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    /**
     * [ id =>, name =>, department_id => ]
     */
    public function select(int $id): array | false
    {
        $this->builder->select([Services::$id, Services::$name, Services::$department_id])
            ->from(Services::$table)
            ->whereEqual(Services::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function update(
        int $id, string $name, int $departmentId
    ): bool
    {
        $this->builder->update(Services::$table)
            ->set(Services::$name, ':name', $name)
            ->andSet(Services::$department_id, ':department_id', $departmentId)
            ->whereEqual(Services::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $this->builder->delete()
            ->from(Services::$table)
            ->whereEqual(Services::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [ id =>, name =>, department_id =>, department_name => ]
     */
    public function selectRow(int $id): array | false
    {
        $this->builder->select([Services::$id, Services::$name,
                                Services::$department_id,
                                Departments::$name],
            [Departments::$name => 'department_name'])
            ->from(Services::$table)
            ->innerJoin(Departments::$table)
                ->on(Services::$department_id, Departments::$id)
            ->whereEqual(Services::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    /**
     * [
     *      0 => [ id =>, name =>, ]
     * ................................
     * ]
     */
    public function selectAllInWorkerDepartment(int $workerId): array | false
    {
        $this->builder->select([Services::$id, Services::$name])
            ->from(Services::$table)
            ->innerJoin(Departments::$table)
                ->on(Services::$department_id, Departments::$id)
            ->innerJoin(Positions::$table)
                ->on(Departments::$id, Positions::$department_id)
            ->innerJoin(Workers::$table)
                ->on(Positions::$id, Workers::$position_id)
            ->whereEqual(Workers::$id, ':id', $workerId)
        ->build();

        return $this->db->manyRows();
    }
}