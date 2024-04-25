<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Affiliates;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Services;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;

class ScheduleRepository extends Repository
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
     * [
     *       0 => [
     *          'schedule_id' =>,
     *          'service_id' =>,
     *          'service_name' =>,
     *          'department_id' =>,
     *          'worker_id' =>,
     *          'worker_name' =>,
     *          'worker_surname' =>,
     *          'affiliate_id' =>,
     *          'city' =>,
     *          'address' =>,
     *          'day' =>,
     *          'start_time' =>,
     *          'end_time' =>,
     *          'price' =>,
     *          'currency' =>
     *       ]
     *  ...............................
     *  ]
     */
    public function selectAll(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null
    ): array | false
    {
        $workerServiceSchedule = WorkersServiceSchedule::$table;
        $schedule_id = WorkersServiceSchedule::$id;
        $schedule_price_id = WorkersServiceSchedule::$price_id;
        $schedule_affiliate_id = WorkersServiceSchedule::$affiliate_id;
        $schedule_day = WorkersServiceSchedule::$day;
        $schedule_start_time = WorkersServiceSchedule::$start_time;
        $schedule_end_time = WorkersServiceSchedule::$end_time;
        $schedule_order_id = WorkersServiceSchedule::$order_id;

        $services = Services::$table;
        $services_id = Services::$id;
        $services_serviceName = Services::$name;
        $services_departmentId = Services::$department_id;

        $workers = Workers::$table;
        $workers_id = Workers::$id;
        $workers_name = Workers::$name;
        $workers_surname = Workers::$surname;

        $affiliates = Affiliates::$table;
        $affiliates_id = Affiliates::$id;
        $affiliates_city = Affiliates::$city;
        $affiliates_address = Affiliates::$address;

        $workersServicePricing = WorkersServicePricing::$table;

        $pricing_id = WorkersServicePricing::$id;
        $pricing_service_id = WorkersServicePricing::$service_id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;
        $pricing_price = WorkersServicePricing::$price;
        $pricing_currency = WorkersServicePricing::$currency;

        $departmentFilter = $this->_departmentFilter($departmentId);
        $serviceFilter = $this->_serviceFilter($serviceId, $pricing_service_id);
        $workerFilter = $this->_workerFilter($workerId, $pricing_worker_id);
        $affiliateFilter = $this->_affiliateFilter($affiliateId, $schedule_affiliate_id);
        $dateFilter = $this->_dateFilter($dateFrom, $dateTo);
        $timeFilter = $this->_timeFilter($timeFrom, $timeTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo
        );

        $this->db->query("
            SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $services_departmentId as department_id,
                   $workers_id as worker_id, $workers_name as worker_name,
                   $workers_surname as worker_surname,
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $workersServicePricing ON $schedule_price_id = $pricing_id
                INNER JOIN $services ON $pricing_service_id = $services_id
                INNER JOIN $workers ON $pricing_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
            
            WHERE $schedule_order_id IS NULL 
                {$departmentFilter['where']}
                    
                {$serviceFilter['where']}
                {$workerFilter['where']}
                {$affiliateFilter['where']}
              
                {$dateFilter['where']}
              
                {$timeFilter['where']}
              
                {$priceFilter['where']}
        ");

        $params = [
            ...$departmentFilter['toBind'], ...$serviceFilter['toBind'],
            ...$workerFilter['toBind'], ...$affiliateFilter['toBind'],
            ...$dateFilter['toBind'], ...$timeFilter['toBind'], ...$priceFilter['toBind']
        ];

        $this->db->bindAll($params);

        return $this->db->manyRows();
    }

    public function updateOrderIdToNull(int $id): bool
    {
        $this->builder->update(WorkersServiceSchedule::$table)
            ->setNull(WorkersServiceSchedule::$order_id)
            ->whereEqual(WorkersServiceSchedule::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectIdByOrderId(int $orderId): int | false
    {
        $this->builder->select([OrdersService::$schedule_id])
            ->from(OrdersService::$table)
            ->whereEqual(OrdersService::$id, ':id', $orderId)
        ->build();

        $result = $this->db->singleRow();
        if($result) {
            return $result[explode('.', OrdersService::$schedule_id)[1]];
        }
        return $result;
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ id =>,  price_id =>, affiliate_id =>, day =>,
     *   start_time =>, end_time =>, order_id => ]
     */
    public function select(int $id): array | false
    {
        $this->builder->select(['*'])
            ->from(WorkersServiceSchedule::$table)
            ->whereEqual(WorkersServiceSchedule::$id, ':schedule_id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function updateOrderId(int $id, int $orderId): bool
    {
        $this->builder->update(WorkersServiceSchedule::$table)
            ->set(WorkersServiceSchedule::$order_id, ':order_id', $orderId)
            ->whereEqual(WorkersServiceSchedule::$id, ':schedule_id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param string $email
     * @param string $startDatetime
     * @param string $endDatetime
     * @return array|false
     *
     * [
     *      0 => [ id => ]
     *      1 => [ id => ]
     * ..........................
     * ]
     */
    public function selectAllByUserEmailAndTime(
        string $email, string $startDatetime, string $endDatetime
    ): array | false
    {
        $ordersService = OrdersService::$table;
        $completed_time = OrdersService::$completed_datetime;
        $canceled_time = OrdersService::$canceled_datetime;
        $order_id = OrdersService::$id;
        $start_datetime = OrdersService::$start_datetime;
        $end_datetime = OrdersService::$end_datetime;
        $email_column = OrdersService::$email;

        $this->db->query("
            SELECT $order_id FROM $ordersService
            WHERE $email_column = :email
            AND $completed_time IS NULL
            AND $canceled_time IS NULL
            AND NOT (
                :end_datetime <= $start_datetime OR
                :start_datetime >= $end_datetime
            );
        ");
        $this->db->bindAll([
            ':email' => $email,
            ':start_datetime' => $startDatetime,
            ':end_datetime' => $endDatetime
        ]);

        return $this->db->manyRows();
    }
}