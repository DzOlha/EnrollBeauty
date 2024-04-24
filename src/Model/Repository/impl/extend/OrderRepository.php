<?php

namespace Src\Model\Repository\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\impl\Repository;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Services;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;

class OrderRepository extends Repository
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
     *
     * [
     *      0 => [id => ]
     *      1 => [id => ]
     * ....................
     * ]
     */
    public function selectAllUpcomingByDepartmentId(int $departmentId): array | false
    {
        $now = date('Y-m-d H:i:s');

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(OrdersService::$price_id, WorkersServicePricing::$id)
            ->innerJoin(Services::$table)
                ->on(WorkersServicePricing::$service_id, Services::$id)
            ->whereEqual(Services::$department_id, ':id', $departmentId)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start', $now)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $workerId
     * @return array|false
     *  [
     *       0 => [id => ]
     *       1 => [id => ]
     *  ....................
     *  ]
     */
    public function selectAllUpcomingByWorkerId(int $workerId): array | false
    {
        $now = date('Y-m-d H:i:s');

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(OrdersService::$price_id, WorkersServicePricing::$id)
            ->whereEqual(WorkersServicePricing::$worker_id, ':id', $workerId)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start', $now)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $positionId
     * @return array|false
     *  [
     *       0 => [id => ]
     *       1 => [id => ]
     *  ....................
     *  ]
     */
    public function selectAllUpcomingByPositionId(int $positionId): array | false
    {
        $now = date('Y-m-d H:i:s');

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(OrdersService::$price_id, WorkersServicePricing::$id)
            ->innerJoin(Workers::$table)
                ->on(WorkersServicePricing::$worker_id, Workers::$id)
            ->whereEqual(Workers::$position_id, ':position_id', $positionId)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start', $now)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $affiliateId
     * @return array|false
     * [
     *        0 => [id => ]
     *        1 => [id => ]
     *   ....................
     *   ]
     */
    public function selectAllUpcomingByAffiliateId(int $affiliateId): array | false
    {
        $now = date('Y-m-d H:i:s');

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->whereEqual(OrdersService::$affiliate_id, ':affiliate_id', $affiliateId)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start', $now)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }
}