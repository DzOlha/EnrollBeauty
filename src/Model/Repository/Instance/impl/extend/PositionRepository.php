<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Departments;
use Src\Model\Table\Positions;

class PositionRepository extends Repository
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
        $this->builder->select([Positions::$id, Positions::$name])
            ->from(Positions::$table)
            ->build();

        return $this->db->manyRows();
    }

    public function selectAllLimited(
        int $limit, int $offset,
        string $orderByField = 'positions.id', string $orderDirection = 'asc'
    ): array | false
    {
        $positions = Positions::$table;
        $queryFrom = " $positions ";

        $this->builder->select([Positions::$id, Positions::$name,
                                Positions::$department_id, Departments::$name],
            [Departments::$name => 'department_name'])
            ->from(Positions::$table)
            ->innerJoin(Departments::$table)
            ->on(Positions::$department_id, Departments::$id)
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

    public function insert(string $name, int $departmentId): int | false
    {
        $this->builder->insertInto(Positions::$table,
            [Positions::$name, Positions::$department_id],)
            ->values([':name', ':department_id'], [$name, $departmentId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function selectIdByNameAndDepartment(string $name, int $departmentId): int | false
    {
        $this->builder->select([Positions::$id])
            ->from(Positions::$table)
            ->whereEqual(Positions::$name, ':name', $name)
            ->andEqual(Positions::$department_id, ':department_id', $departmentId)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            // positions.id -> id
            return $result[explode('.', Positions::$id)[1]];
        }
        return $result;
    }

    /**
     * @param int $id
     * @return array|false
     * [ id => , name => , department_id => ]
     */
    public function select(int $id): array | false
    {
        $this->builder->select([Positions::$id, Positions::$name, Positions::$department_id])
            ->from(Positions::$table)
            ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function update(int $id, string $name, int $departmentId): bool
    {
        $this->builder->update(Positions::$table)
            ->set(Positions::$name, ':name', $name)
            ->andSet(Positions::$department_id, ':department_id', $departmentId)
            ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return array|false
     * [ id => , name => , department_id => , department_name => ]
     */
    public function selectWithDepartment(int $id): array | false
    {
        $this->builder->select([Positions::$id, Positions::$name, Positions::$department_id,
                                Departments::$name],
            [Departments::$name => 'department_name'])
            ->from(Positions::$table)
            ->innerJoin(Departments::$table)
                ->on(Positions::$department_id, Departments::$id)
            ->whereEqual(Positions::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function delete(int $id): bool
    {
        $this->builder->delete()
            ->from(Positions::$table)
            ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}