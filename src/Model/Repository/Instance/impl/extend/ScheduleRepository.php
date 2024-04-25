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
    public function selectDetails(int $id): array | false
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

    protected function _workerTimeFilter($timeFrom, $timeTo): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];
        $schedule_start_time = WorkersServiceSchedule::$start_time;
        $schedule_end_time = WorkersServiceSchedule::$end_time;

        $setFrom = ($timeFrom !== null && $timeFrom !== '');
        $setTo = ($timeTo !== null && $timeTo !== '');

        if($setFrom) {
            $result['where'] = " AND $schedule_start_time >= :time_from ";
            $result['toBind'] += [
                ':time_from' => $timeFrom
            ];
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_end_time <= :time_to ";
            $result['toBind'] += [
                ':time_to' => $timeTo
            ];
        }

        return $result;
    }

    /**
     * [
     *       0 => [
     *          'schedule_id' =>,
     *          'order_id' =>,
     *          'service_id' =>,
     *          'department_id' =>,
     *          'service_name' =>,
     *          'user_id' =>,
     *          'user_email' =>,
     *          'affiliate_id' =>,
     *          'city' =>,
     *          'address' =>,
     *          'day' =>,
     *          'start_time' =>,
     *          'end_time' =>,
     *          'price' =>,
     *          'currency' =>
     *       ]
     *  ..................................
     *  ]
     */
    public function selectAllOrdered(
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

        $orders = OrdersService::$table;
        $ordersOrderId = OrdersService::$id;
        $ordersUserId = OrdersService::$user_id;
        $ordersUserEmail = OrdersService::$email;
        $completedTime = OrdersService::$completed_datetime;

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
        $timeFilter = $this->_workerTimeFilter($timeFrom, $timeTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo
        );

        $this->db->query("
            SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $ordersOrderId as order_id, $ordersUserId as user_id, $ordersUserEmail as user_email,
                   $services_departmentId as department_id, 
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $workersServicePricing ON $schedule_price_id = $pricing_id
                INNER JOIN $services ON $pricing_service_id = $services_id
                INNER JOIN $workers ON $pricing_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $orders ON $schedule_order_id = $ordersOrderId
            
            WHERE $schedule_order_id IS NOT NULL AND $completedTime IS NULL
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

    /**
     * @param int $id
     * @return array|false
     * [  schedule_id =>,
     *          order_id => can be null,
     *    service_id =>,
     *    department_id =>,
     *    service_name =>,
     *          user_id => can be null,
     *          user_email => can be null,
     *    affiliate_id =>,
     *    city =>,
     *    address =>,
     *    day =>,
     *    start_time =>,
     *    end_time =>,
     *    price =>,
     *    currency => ]
     */
    public function selectWorkerCard(int $id): array | false
    {
        $workerServiceSchedule = WorkersServiceSchedule::$table;
        $workerServiceScheduleId = WorkersServiceSchedule::$id;

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

        $orders = OrdersService::$table;
        $ordersOrderId = OrdersService::$id;
        $ordersUserId = OrdersService::$user_id;
        $ordersUserEmail = OrdersService::$email;

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

        $this->db->query("
            SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $ordersOrderId as order_id, $ordersUserId as user_id, $ordersUserEmail as user_email,
                   $services_departmentId as department_id, 
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $workersServicePricing ON $schedule_price_id = $pricing_id
                INNER JOIN $services ON $pricing_service_id = $services_id
                INNER JOIN $workers ON $pricing_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                LEFT JOIN $orders ON $schedule_order_id = $ordersOrderId
            
            WHERE $workerServiceScheduleId = :id
        ");
        $this->db->bind(':id', $id);

        return $this->db->singleRow();
    }

    public function selectAllFree(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null,
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
        $timeFilter = $this->_workerTimeFilter($timeFrom, $timeTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo
        );

        $this->db->query("
            SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $services_departmentId as department_id,
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

    /**
     * [
     *        0 => [
     *            'start_time' =>
     *            'end_time' =>
     *        ]
     *    ........................
     *   ]
     */
    public function selectBusyIntervalsByWorkerId(int $workerId, string $day): array | false
    {
        $this->builder->select([WorkersServiceSchedule::$start_time,
                                WorkersServiceSchedule::$end_time])
            ->from(WorkersServiceSchedule::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(WorkersServiceSchedule::$price_id, WorkersServicePricing::$id)
            ->leftJoin(OrdersService::$table)
                ->on(WorkersServiceSchedule::$order_id, OrdersService::$id)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->andEqual(WorkersServiceSchedule::$day, ':day', $day)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * [
     *      0 => [ id => ]
     *      1 => [ id => ]
     * .........................
     * ]
     */
    public function selectAllByWorkerDayTime(
        int $workerId, string $day, string $startTime, string $endTime
    ): array | false
    {
        $pricing = WorkersServicePricing::$table;
        $pricing_id = WorkersServicePricing::$id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;

        $orders = OrdersService::$table;
        $orders_id = OrdersService::$id;
        $orders_completed = OrdersService::$completed_datetime;
        $orders_canceled = OrdersService::$canceled_datetime;

        $schedule = WorkersServiceSchedule::$table;
        $schedule_price_id = WorkersServiceSchedule::$price_id;
        $schedule_order_id = WorkersServiceSchedule::$order_id;
        $start_time = WorkersServiceSchedule::$start_time;
        $end_time = WorkersServiceSchedule::$end_time;
        $day_column = WorkersServiceSchedule::$day;
        $id = WorkersServiceSchedule::$id;

        $this->db->query("
            SELECT $id FROM $schedule
            INNER JOIN $pricing ON $schedule_price_id = $pricing_id
            LEFT JOIN $orders ON $schedule_order_id = $orders_id
            WHERE $pricing_worker_id = :worker_id
            AND $day_column = :day
            AND $orders_canceled IS NULL
            AND $orders_completed IS NULL
            AND NOT (
                :end_time <= $start_time OR
                :start_time >= $end_time
            );
        ");
        $this->db->bind(':worker_id', $workerId);
        $this->db->bind(':day', $day);
        $this->db->bind(':start_time', $startTime);
        $this->db->bind(':end_time', $endTime);

        return $this->db->manyRows();
    }

    public function insert(
        int    $priceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    ): int | false
    {
        $this->builder->insertInto(WorkersServiceSchedule::$table,
            [
                WorkersServiceSchedule::$price_id,
                WorkersServiceSchedule::$affiliate_id, WorkersServiceSchedule::$day,
                WorkersServiceSchedule::$start_time, WorkersServiceSchedule::$end_time
            ])
            ->values(
                [':price_id', ':affiliate_id',
                 ':day', ':start_time', ':end_time'],
                [$priceId, $affiliateId, $day, $startTime, $endTime]
            )
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function update(
        int $id, int $priceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    ): bool
    {
        $this->builder->update(WorkersServiceSchedule::$table)
            ->set(WorkersServiceSchedule::$price_id, ':price_id', $priceId)
            ->andSet(WorkersServiceSchedule::$affiliate_id, ':affiliate_id', $affiliateId)
            ->andSet(WorkersServiceSchedule::$day, ':day', $day)
            ->andSet(WorkersServiceSchedule::$start_time, ':start', $startTime)
            ->andSet(WorkersServiceSchedule::$end_time, ':end', $endTime)
            ->whereEqual(WorkersServiceSchedule::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [
     *      0 => [ id => ],
     *      1 => [ id => ],
     * .........................
     * ]
     */
    public function selectBusyIntervalsByWorkerDayNotId(
        int $workerId, string $day, int $scheduleId
    ): array | false
    {
        $this->builder->select([WorkersServiceSchedule::$start_time,
                                WorkersServiceSchedule::$end_time])
            ->from(WorkersServiceSchedule::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(WorkersServiceSchedule::$price_id, WorkersServicePricing::$id)
            ->leftJoin(OrdersService::$table)
                ->on(WorkersServiceSchedule::$order_id, OrdersService::$id)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->andEqual(WorkersServiceSchedule::$day, ':day', $day)
            ->andNotEqual(WorkersServiceSchedule::$id, ':id', $scheduleId)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * [
     *      0 => [ id => ],
     *      1 => [ id => ],
     * ........................
     * ]
     */
    public function selectAllByWorkerAndTimeNotId(
        int $workerId, string $day, string $startTime,
        string $endTime, int $scheduleId
    ): array | false
    {
        $pricing = WorkersServicePricing::$table;
        $pricing_id = WorkersServicePricing::$id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;

        $orders = OrdersService::$table;
        $orders_id = OrdersService::$id;
        $orders_completed = OrdersService::$completed_datetime;
        $orders_canceled = OrdersService::$canceled_datetime;

        $schedule = WorkersServiceSchedule::$table;
        $schedule_price_id = WorkersServiceSchedule::$price_id;
        $schedule_order_id = WorkersServiceSchedule::$order_id;
        $start_time = WorkersServiceSchedule::$start_time;
        $end_time = WorkersServiceSchedule::$end_time;
        $day_column = WorkersServiceSchedule::$day;
        $id = WorkersServiceSchedule::$id;

        $this->db->query("
            SELECT $id FROM $schedule
            INNER JOIN $pricing ON $schedule_price_id = $pricing_id
            LEFT JOIN $orders ON $schedule_order_id = $orders_id
            WHERE $pricing_worker_id = :worker_id
            AND $day_column = :day
            AND $id != :schedule_id
            AND $orders_canceled IS NULL
            AND $orders_completed IS NULL
            AND NOT (
                :end_time <= $start_time OR
                :start_time >= $end_time
            );
        ");

        $this->db->bindAll([
            ':worker_id' => $workerId,
            ':day' => $day,
            ':schedule_id' => $scheduleId,
            ':start_time' => $startTime,
            ':end_time' => $endTime
        ]);

        return $this->db->manyRows();
    }

    public function delete(int $id): bool
    {
        $this->builder->delete()
            ->from(WorkersServiceSchedule::$table)
            ->whereEqual(WorkersServiceSchedule::$id, ':id', $id)
            ->andIsNull(WorkersServiceSchedule::$order_id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}