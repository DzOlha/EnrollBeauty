<?php

namespace Src\Model\Repository\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\impl\Repository;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Workers;

class AffiliateRepository extends Repository
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
     * {
     *     0: {
     *      id:
     *      name:
     *      country:
     *      city:
     *      address:
     *      created_date:
     *      manager_id:
     *      manager_name:
     *      manager_surname:
     *    },
     * ...........
     *    totalRowsCount =>
     * }
     */
    public function selectAllLimited(
        int    $limit, int $offset,
        string $orderByField = 'affiliates.id', string $orderDirection = 'asc'
    ): array|false
    {
        $affiliates = Affiliates::$table;
        $queryFrom = " $affiliates ";

        $this->builder->select([Affiliates::$id, Affiliates::$name, Affiliates::$country,
                                Affiliates::$city, Affiliates::$address, Affiliates::$created_date,
                                Workers::$id, Workers::$name, Workers::$surname],
            [Workers::$id      => 'manager_id',
             Workers::$name    => 'manager_name',
             Workers::$surname => 'manager_surname'])
            ->from(Affiliates::$table)
            ->leftJoin(Workers::$table)
            ->on(Affiliates::$worker_manager_id, Workers::$id)
            ->orderBy($orderByField, $orderDirection)
            ->limit($limit)
            ->offset($offset)
            ->build();

        $result = $this->db->manyRows();
        if ($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result);
    }

    public function insert(
        string $name, string $country, string $city, string $address, ?int $managerId = null
    ): int|false
    {
        $this->builder->insertInto(Affiliates::$table,
            [Affiliates::$name, Affiliates::$country,
             Affiliates::$city, Affiliates::$worker_manager_id,
             Affiliates::$address])
            ->values([':name', ':country', ':city', ':manager_id', ':address'],
                [$name, $country, $city, $managerId, $address])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ id => , name => , country => , city => , address => , manager_id => ]
     */
    public function select(int $id): array|false
    {
        $this->builder->select([Affiliates::$id, Affiliates::$name, Affiliates::$country,
                                Affiliates::$city, Affiliates::$address,
                                Affiliates::$worker_manager_id],
            [Affiliates::$worker_manager_id => 'manager_id'])
            ->from(Affiliates::$table)
            ->whereEqual(Affiliates::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ id => , name => , country => , city => , address => ,
     *   created_date => , manager_id => , manager_name, manager_surname => ]
     */
    public function selectRow(int $id): array|false
    {
        $this->builder->select([Affiliates::$id, Affiliates::$name, Affiliates::$country,
                                Affiliates::$city, Affiliates::$address, Affiliates::$created_date,
                                Workers::$id, Workers::$name, Workers::$surname],
            [
                Workers::$id      => 'manager_id',
                Workers::$name    => 'manager_name',
                Workers::$surname => 'manager_surname'
            ])
            ->from(Affiliates::$table)
            ->leftJoin(Workers::$table)
            ->on(Affiliates::$worker_manager_id, Workers::$id)
            ->whereEqual(Affiliates::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $id
     * @param string $country
     * @param string $city
     * @param string $address
     * @return array|false
     *
     * [ id => ]
     */
    public function selectIdByAddressAndNotId(
        int $id, string $country, string $city, string $address
    ): array|false
    {
        $this->builder->select([Affiliates::$id])
            ->from(Affiliates::$table)
            ->whereEqual(Affiliates::$country, ':country', $country)
            ->andEqual(Affiliates::$city, ':city', $city)
            ->andEqual(Affiliates::$address, ':address', $address)
            ->andNotEqual(Affiliates::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    /**
     * @param string $country
     * @param string $city
     * @param string $address
     * @return array|false
     *
     * [ id => ]
     */
    public function selectIdByAddress(
        string $country, string $city, string $address
    ): array|false
    {
        $this->builder->select([Affiliates::$id])
            ->from(Affiliates::$table)
            ->whereEqual(Affiliates::$country, ':country', $country)
            ->andEqual(Affiliates::$city, ':city', $city)
            ->andEqual(Affiliates::$address, ':address', $address)
        ->build();

        return $this->db->singleRow();
    }

    public function update(
        int $id, string $name, string $country,
        string $city, string $address, ?int $managerId = null
    ) : bool
    {
        $this->builder->update(Affiliates::$table)
            ->set(Affiliates::$name, ':name', $name)
            ->andSet(Affiliates::$country, ':country', $country)
            ->andSet(Affiliates::$city, ':city', $city)
            ->andSet(Affiliates::$address, ':address', $address)
            ->andSet(Affiliates::$worker_manager_id, ':manager_id', $managerId)
            ->whereEqual(Affiliates::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $this->builder->delete()
            ->from(Affiliates::$table)
            ->whereEqual(Affiliates::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}