<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Departments;
use Src\Model\Table\Services;

class DepartmentRepository extends Repository
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
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false
     *
     * [
     *      0 => [ id => , name => ]
     *      1 => [ id => , name => ]
     *...................................
     *      totalRowsCount =>
     * ]
     */
    public function selectAllLimited(
        int $limit, int $offset,
        string $orderByField = 'departments.id', string $orderDirection = 'asc'
    ): array | false
    {
        $departments = Departments::$table;
        $queryFrom = " $departments ";

        $this->builder->select([Departments::$id, Departments::$name])
            ->from(Departments::$table)
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

    public function insert(
        string $name, string $description, string $photoFilename
    ): int | false
    {
        $this->builder->insertInto(Departments::$table,
            [Departments::$name, Departments::$description, Departments::$photo_filename])
            ->values([':name', ':description', ':photo'],
                [$name, $description, $photoFilename])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function update(int $id, string $name, string $description): bool
    {
        $this->builder->update(Departments::$table)
            ->set(Departments::$name, ':name', $name)
            ->andSet(Departments::$description, ':description', $description)
            ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectPhoto(int $id): string | false
    {
        $this->builder->select([Departments::$photo_filename])
            ->from(Departments::$table)
            ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            return $result[explode('.', Departments::$photo_filename)[1]];
        }
        return $result;
    }

    public function updatePhoto(int $id, string $photoFilename): bool
    {
        $this->builder->update(Departments::$table)
            ->set(Departments::$photo_filename, ':photo', $photoFilename)
            ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function delete(int $id)
    {
        $this->builder->delete()
            ->from(Departments::$table)
            ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ id => , name =>, description =>, photo_filename => ]
     */
    public function selectWithPhoto(int $id): array | false
    {
        $this->builder->select([Departments::$id, Departments::$name,
                                Departments::$description, Departments::$photo_filename])
            ->from(Departments::$table)
            ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $limit
     * @return array|false
     *
     * [
     *      0 => [ id => , name =>, description =>, photo_filename => ]
     *      1 => [ id => , name =>, description =>, photo_filename => ]
     * ................................................................
     * ]
     */
    public function selectAllLimitedWithPhoto(int $limit): array | false
    {
        $this->builder->select([Departments::$id, Departments::$name,
                                Departments::$description, Departments::$photo_filename])
            ->from(Departments::$table)
            ->whereNotNull(Departments::$photo_filename)
            ->andNotEmpty(Departments::$photo_filename)
            ->limit($limit)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @return array|false
     * [
     *      0 => [ id =>, name => ]
     *      1 => [ id =>, name => ]
     * .............................
     * ]
     */
    public function selectAll(): array | false
    {
        $this->builder->select([Departments::$id, Departments::$name])
            ->from(Departments::$table)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $serviceId
     * @return array|false
     *
     * [ id =>, name => ]
     */
    public function selectByServiceId(int $serviceId): array | false
    {
        $this->builder->select([Departments::$id, Departments::$name])
            ->from(Departments::$table)
            ->innerJoin(Services::$table)
                ->on(Departments::$id, Services::$department_id)
            ->whereEqual(Services::$id, ':service_id', $serviceId)
        ->build();

        return $this->db->singleRow();
    }
}