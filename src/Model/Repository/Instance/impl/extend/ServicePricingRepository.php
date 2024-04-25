<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Departments;
use Src\Model\Table\Services;
use Src\Model\Table\WorkersServicePricing;

class ServicePricingRepository extends Repository
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
     *
     * [
     *      0 => [
     *          id =>,
                name =>,
                services => [
     *              id =>
     *              name =>
     *              min_price =>
     *              currency =>
                ]
     *      ]
     * ]
     */
    public function selectAllMinPricelist(): array | false
    {
        $workers_service_pricing = WorkersServicePricing::$table;
        $services = Services::$table;
        $departments = Departments::$table;

        $this->db->query("
            SELECT 
                d.id,
                d.name,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', s.id,
                        'name', s.name,
                        'min_price', min_price.min_price,
                        'currency', min_price.currency
                    )
                ) AS services
            FROM 
                $departments d
            INNER JOIN 
                $services s ON d.id = s.department_id
            INNER JOIN 
            (
                SELECT
                    service_id,
                    MIN(price) AS min_price,
                    currency
                FROM 
                    $workers_service_pricing
                GROUP BY 
                    service_id, currency
            ) min_price ON s.id = min_price.service_id
            GROUP BY 
                d.id

        ");

        return $this->db->manyRows();
    }

    public function selectIdByWorkerAndService(int $workerId, int $serviceId): int | false
    {
        $this->builder->select([WorkersServicePricing::$id])
            ->from(WorkersServicePricing::$table)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->andEqual(WorkersServicePricing::$service_id, ':service_id', $serviceId)
        ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // workers_service_pricing.id -> id
            return $result[explode('.', WorkersServicePricing::$id)[1]];
        }
        return false;
    }

    public function insert(
        int $workerId, int $serviceId, $price
    ): int | false
    {
        $this->builder->insertInto(WorkersServicePricing::$table, [
            WorkersServicePricing::$worker_id, WorkersServicePricing::$service_id,
            WorkersServicePricing::$price
        ])
            ->values([':worker_id', ':service_id', ':price'],
                [$workerId, $serviceId, $price])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    /**
     * [
     *  0 => [
     *       id =>
     *       name => service name
     *       service_id =>
     *       price =>
     *       currency =>
     *       updated_datetime =>
     *   ]
     *  ............................
     *   totalRowsCount =>
     *  ]
     */
    public function selectAllLimitedByWorkerId(
        int    $workerId, int $limit, int $offset,
        string $orderByField = 'workers_service_pricing.id', string $orderDirection = 'asc'
    ): array | false
    {
        $pricingTable = WorkersServicePricing::$table;
        $servicesTable = Services::$table;

        $worker_id = WorkersServicePricing::$worker_id;

        $service_id = Services::$id;
        $pricing_service_id = WorkersServicePricing::$service_id;

        $queryFrom = "
            $pricingTable INNER JOIN $servicesTable ON $pricing_service_id = $service_id
            WHERE $worker_id = :worker_id
        ";
        $params = [
            ':worker_id' => $workerId
        ];

        $this->builder->select([
            WorkersServicePricing::$id, "$service_id as service_id", Services::$name,
            WorkersServicePricing::$price,
            WorkersServicePricing::$currency, WorkersServicePricing::$updated_datetime
        ])
            ->from(WorkersServicePricing::$table)
            ->innerJoin(Services::$table)
                ->on(WorkersServicePricing::$service_id, Services::$id)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->orderBy($orderByField, $orderDirection)
            ->limit($limit)
            ->offset($offset)
        ->build();

        $result = $this->db->manyRows();
        if ($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result, $params);
    }

    public function selectIdByWorkerService(
        int $workerId, int $serviceId
    ): int | false
    {
        $this->builder->select([WorkersServicePricing::$id])
            ->from(WorkersServicePricing::$table)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->andEqual(WorkersServicePricing::$service_id, ':service_id', $serviceId)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            /**
             * workers_service_pricing.id -> id
             */
            return $result[explode('.', WorkersServicePricing::$id)[1]];
        }
        return $result;
    }

    public function update(
        int $workerId, int $serviceId, $price
    ): bool
    {
        $this->builder->update(WorkersServicePricing::$table)
            ->set(WorkersServicePricing::$price, ':price', $price)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->andEqual(WorkersServicePricing::$service_id, ':service_id', $serviceId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $this->builder->delete()
            ->from(WorkersServicePricing::$table)
            ->whereEqual(WorkersServicePricing::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [ id =>, name =>, price =>, currency =>, updated_datetime =>, service_id => ]
     */
    public function selectByWorkerAndService(
        int $workerId, int $serviceId
    ): array | false
    {
        $this->builder->select([WorkersServicePricing::$id, Services::$name,
                                WorkersServicePricing::$price, WorkersServicePricing::$currency,
                                WorkersServicePricing::$updated_datetime, WorkersServicePricing::$service_id])
            ->from(WorkersServicePricing::$table)
            ->innerJoin(Services::$table)
            ->on(WorkersServicePricing::$service_id, Services::$id)
            ->whereEqual(WorkersServicePricing::$worker_id, ":worker_id", $workerId)
            ->andEqual(WorkersServicePricing::$service_id, ':service_id', $serviceId)
            ->build();

        return $this->db->singleRow();
    }
}