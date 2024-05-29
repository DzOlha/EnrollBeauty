<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Departments;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Services;
use Src\Model\Table\Users;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;

class OrderServiceRepository extends Repository
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

    public function updateCancel(int $id): bool
    {
        $currentDatetime = date('Y-m-d H:i:s');
        $canceled = -1;

        $this->builder->update(OrdersService::$table)
            ->set(OrdersService::$canceled_datetime, ':canceled_datetime', $currentDatetime)
            ->andSet(OrdersService::$status, ':status', $canceled)
            ->whereEqual(OrdersService::$id, ':order_id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateComplete(int $id): bool
    {
        $now = date('Y-m-d H:i:s');
        $completed = 1;

        $this->builder->update(OrdersService::$table)
            ->set(OrdersService::$completed_datetime, ':completed', $now)
            ->andSet(OrdersService::$status, ':status', $completed)
            ->whereEqual(OrdersService::$id, ':id', $id)
            ->andLess(OrdersService::$start_datetime, ':start', $now)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateCancelMany(array $ids): bool
    {
        $now = date('Y-m-d H:i:s');
        $canceled = -1;

        $upcoming = 0;

        $this->builder->update(OrdersService::$table)
            ->set(OrdersService::$canceled_datetime, ':canceled', $now)
            ->andSet(OrdersService::$status, ':status', $canceled)
            ->whereIn(OrdersService::$id, $ids)
            ->andEqual(OrdersService::$status, ':old_status', $upcoming)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateCompleteMany(array $ids): bool
    {
        $now = date('Y-m-d H:i:s');
        $completed = 1;

        $upcoming = 0;

        $this->builder->update(OrdersService::$table)
            ->set(OrdersService::$completed_datetime, ':completed', $now)
            ->andSet(OrdersService::$status, ':status', $completed)
            ->whereIn(OrdersService::$id, $ids)
            ->andEqual(OrdersService::$status, ':old_status', $upcoming)
            ->andLess(OrdersService::$start_datetime, ':start', $now)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [
     *      0 => [ id =>, service_id =>, service_name =>,
     *             worker_id =>, worker_name =>,
     *             worker_surname =>, worker_email =>,
     *             user_id =>, user_name =>,
     *             user_surname =>, user_email =>,
     *             day =>, start_time =>, emd_time =>,
     *             affiliate_id =>, city =>, address =>,
     *             department_d =>, department_name =>,
     *             price =>, currency =>, status => ]
     */
    public function selectAllLimited(
        $limit, $offset,
        $orderField = 'orders_service.id', $orderDirection = 'asc',
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $priceFrom = null, $priceTo = null,
        $userId = null, $status = null
    ): array | false
    {
        $workerServiceSchedule = WorkersServiceSchedule::$table;
        $schedule_id = WorkersServiceSchedule::$id;
        $schedule_price_id = WorkersServiceSchedule::$price_id;
        $schedule_affiliate_id = WorkersServiceSchedule::$affiliate_id;
        $schedule_day = WorkersServiceSchedule::$day;
        $schedule_start_time = WorkersServiceSchedule::$start_time;
        $schedule_end_time = WorkersServiceSchedule::$end_time;

        $ordersTable = OrdersService::$table;
        $ordersId = OrdersService::$id;
        $orders_schedule_id = OrdersService::$schedule_id;
        $order_user_id = OrdersService::$user_id;
        $orders_status = OrdersService::$status;

        $departments = Departments::$table;
        $departments_id = Departments::$id;
        $departments_name = Departments::$name;

        $affiliates = Affiliates::$table;
        $affiliates_id = Affiliates::$id;
        $affiliates_city = Affiliates::$city;
        $affiliates_address = Affiliates::$address;

        $users = Users::$table;
        $users_id = Users::$id;
        $users_name = Users::$name;
        $users_surname = Users::$surname;
        $users_email = Users::$email;

        $services = Services::$table;
        $services_id = Services::$id;
        $services_serviceName = Services::$name;
        $services_depId = Services::$department_id;

        $workers = Workers::$table;
        $workers_id = Workers::$id;
        $workers_name = Workers::$name;
        $workers_surname = Workers::$surname;
        $workers_email = Workers::$email;

        $workersServicePricing = WorkersServicePricing::$table;

        $pricing_id = WorkersServicePricing::$id;
        $pricing_service_id = WorkersServicePricing::$service_id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;
        $pricing_price = WorkersServicePricing::$price;
        $pricing_currency = WorkersServicePricing::$currency;

        $departmentFilter = $this->_departmentFilter($departmentId);
        $serviceFilter = $this->_serviceFilter($serviceId, $pricing_service_id);
        $workerFilter = $this->_workerFilter($workerId, $pricing_worker_id);
        $userFilter = $this->_userFilter($userId, $order_user_id);
        $statusFilter = $this->_statusFilter($status, $orders_status);
        $affiliateFilter = $this->_affiliateFilter($affiliateId, $schedule_affiliate_id);
        $dateFilter = $this->_dateFilter($dateFrom, $dateTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo
        );

        // clear order by and order direction to prevent SQL-injection
        $trimmed = $this->builder->clearOrderBy($orderField, $orderDirection);

        $queryFrom = " $ordersTable
                INNER JOIN $workerServiceSchedule ON $orders_schedule_id = $schedule_id
                INNER JOIN $workersServicePricing ON $schedule_price_id = $pricing_id
                INNER JOIN $services ON $pricing_service_id = $services_id
                INNER JOIN $workers ON $pricing_worker_id = $workers_id 
                INNER JOIN $users ON $order_user_id = $users_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $departments ON $services_depId = $departments_id
            
            WHERE 0=0
                {$departmentFilter['where']}
                    
                {$serviceFilter['where']}
                {$workerFilter['where']}
                {$affiliateFilter['where']}
              
                {$dateFilter['where']}
              
                {$priceFilter['where']}
                
                {$userFilter['where']}
            
                {$statusFilter['where']}
            
           ";

        $this->db->query("
            SELECT $ordersId, 
                   $services_id as service_id,
                   $services_serviceName as service_name,
                   $workers_id as worker_id, 
                   $workers_name as worker_name,
                   $workers_surname as worker_surname,
                   $workers_email as worker_email,
                   $order_user_id as user_id,
                   $users_name as user_name,
                   $users_surname as user_surname,
                   $users_email as user_email,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $departments_id as department_id,
                   $departments_name as department_name,
                   $pricing_price, $pricing_currency,
                   $orders_status
            
            FROM $ordersTable
                INNER JOIN $workerServiceSchedule ON $orders_schedule_id = $schedule_id
                INNER JOIN $workersServicePricing ON $schedule_price_id = $pricing_id
                INNER JOIN $services ON $pricing_service_id = $services_id
                INNER JOIN $workers ON $pricing_worker_id = $workers_id 
                INNER JOIN $users ON $order_user_id = $users_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $departments ON $services_depId = $departments_id
            
            WHERE 0=0
                {$departmentFilter['where']}
                    
                {$serviceFilter['where']}
                {$workerFilter['where']}
                {$affiliateFilter['where']}
              
                {$dateFilter['where']}
              
                {$priceFilter['where']}
                
                {$userFilter['where']}
            
                {$statusFilter['where']}
            
            ORDER BY 
                {$trimmed['field']} {$trimmed['dir']}
            
            LIMIT 
                :limit_
            OFFSET 
                :offset_;
            ");

        $params = [
            ...$departmentFilter['toBind'], ...$serviceFilter['toBind'],
            ...$workerFilter['toBind'], ...$affiliateFilter['toBind'],
            ...$dateFilter['toBind'], ...$userFilter['toBind'],
            ...$priceFilter['toBind'], ...$statusFilter['toBind']
        ];

        $this->db->bindAll(
            array_merge($params, [
                ':limit_' => $limit,
                ':offset_' => $offset
            ])
        );

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        $result = $this->_appendTotalRowsCount($queryFrom, $result, $params);
        if($result) {
            return $this->_appendTotalRowsSum(
                $queryFrom, $result, WorkersServicePricing::$price, $params
            );
        }
        return false;
    }

    public function deleteMany(array $ids): bool
    {
        $now = date('Y-m-d H:i:s');

        $upcoming = 0;

        $completed = 1;
        $canceled = -1;

        $this->builder->delete(OrdersService::$table)
            ->from(OrdersService::$table)
            ->whereIn(OrdersService::$id,  $ids)
            ->andEqual(OrdersService::$status, ':completed_status', $completed)
            ->or()->equal(OrdersService::$status, ':canceled_status', $canceled)
            ->or()->subqueryBegin()
                ->equal(OrdersService::$status, ':status', $upcoming)
                ->andLess(OrdersService::$end_datetime, ':end', $now)
            ->subqueryEnd()
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [ 0 => [
     *     'id' =>, 'service_id' =>, 'service_name' =>
     *     'worker_id' =>, 'worker_name' =>, 'worker_surname' =>
     *     'affiliate_id' =>, 'affiliate_city' =>, 'affiliate_address' =>
     *     'start_datetime' =>, 'end_datetime' =>, 'price' =>, 'currency' =>
     *   ],
     * ........................................................................
     *   totalRowsCount =>
     * ]
     */
    public function selectAllUpcomingLimitedByUserId(
        int    $userId, int $limit, int $offset,
        string $orderByField = 'orders_service.id', string $orderDirection = 'asc'
    ): array | false
    {
        $ordersService = OrdersService::$table;
        $user_id = OrdersService::$user_id;
        $price_id = OrdersService::$price_id;
        $affiliate_id = OrdersService::$affiliate_id;
        $start_datetime = OrdersService::$start_datetime;
        $canceled = OrdersService::$canceled_datetime;
        $completed = OrdersService::$completed_datetime;

        $workersPricing = WorkersServicePricing::$table;
        $workersPricingId = WorkersServicePricing::$id;
        $workersPricingWorkerId = WorkersServicePricing::$worker_id;
        $workersPricingServiceId = WorkersServicePricing::$service_id;

        $workers = Workers::$table;
        $workerId = Workers::$id;

        $affiliates = Affiliates::$table;
        $affiliateId = Affiliates::$id;

        $services = Services::$table;
        $serviceId = Services::$id;

        $now = date("Y-m-d H:i:s", time());

        $queryFrom = "
            $ordersService 
                INNER JOIN $workersPricing ON $price_id = $workersPricingId
                INNER JOIN $workers ON $workersPricingWorkerId = $workerId
                INNER JOIN $affiliates ON $affiliate_id = $affiliateId
                INNER JOIN $services ON $workersPricingServiceId = $serviceId
                                         
            WHERE $user_id = :user_id
                AND $canceled IS NULL
                AND $completed IS NULL
                AND $start_datetime >= :now
        ";
        $params = [
            ':user_id' => $userId,
            ':now' => $now
        ];

        $currentDatetime = date('Y-m-d H:i:s');
        $this->builder->select(
            [OrdersService::$id, Services::$id, Services::$name, Workers::$id,
             Workers::$name, Workers::$surname, OrdersService::$affiliate_id, Affiliates::$city,
             Affiliates::$address, OrdersService::$start_datetime, OrdersService::$end_datetime,
             WorkersServicePricing::$price, WorkersServicePricing::$currency],
            [
                Services::$id => 'service_id', Services::$name => 'service_name',
                Workers::$id => 'worker_id', Workers::$name => 'worker_name',
                Workers::$surname => 'worker_surname', OrdersService::$affiliate_id => 'affiliate_id',
                Affiliates::$city => 'affiliate_city', Affiliates::$address => 'affiliate_address'
            ]
        )
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
            ->on(OrdersService::$price_id, WorkersServicePricing::$id)

            ->innerJoin(Workers::$table)
            ->on(WorkersServicePricing::$worker_id, Workers::$id)

            ->innerJoin(Affiliates::$table)
            ->on(OrdersService::$affiliate_id, Affiliates::$id)

            ->innerJoin(Services::$table)
            ->on(WorkersServicePricing::$service_id, Services::$id)

            ->whereEqual(OrdersService::$user_id, ':user_id', $userId)
            ->andIsNull(OrdersService::$canceled_datetime)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start_datetime', $currentDatetime)
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

    /**
     * @param int $scheduleId
     * @return array|false
     *
     * [ id => ]
     */
    public function selectByScheduleId(int $scheduleId): array | false
    {
        $id = OrdersService::$id;

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->whereEqual(OrdersService::$schedule_id, ':schedule_id', $scheduleId)
            ->andIsNull(OrdersService::$canceled_datetime)
            ->andIsNull(OrdersService::$completed_datetime)
        ->build();

        $result = $this->db->singleRow();
        if($result) {
            return $result[explode('.', $id)[1]];
        }
        return $result;
    }

    public function insert(
        ?int $scheduleId, ?int $userId, string $email, int $priceId,
        int $affiliateId, string $startDatetime, string $endDatetime
    ): int | false
    {
        $currentDatetime = date('Y-m-d H:i:s');
        $this->builder->insertInto(OrdersService::$table,
            [
                OrdersService::$user_id, OrdersService::$schedule_id,
                OrdersService::$email, OrdersService::$price_id,
                OrdersService::$affiliate_id, OrdersService::$start_datetime,
                OrdersService::$end_datetime, OrdersService::$created_datetime
            ]
        )
            ->values(
                [':user_id', ':schedule_id', ':email', ':price_id',
                 ':affiliate_id', ':start', ':end', ':created_datetime'],
                [$userId, $scheduleId, $email, $priceId,
                 $affiliateId, $startDatetime, $endDatetime, $currentDatetime]
            )
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
     * [ id =>, email =>, start_datetime =>, price =>, currency =>, city =>,
     *   address =>, user_name =>, user_surname =>,
     *   worker_id =>, worker_name =>, worker_surname =>,service_name => ]
     */
    public function select(int $id): array | false
    {
        $this->builder->select([
            OrdersService::$id, OrdersService::$email, OrdersService::$start_datetime,
            Affiliates::$city, Affiliates::$address,
            Users::$name, Users::$surname,
            WorkersServicePricing::$price, WorkersServicePricing::$currency,
            Workers::$id, Workers::$name, Workers::$surname, Services::$name
        ], [
            Workers::$id => 'worker_id',
            Workers::$name => 'worker_name',
            Workers::$surname => 'worker_surname',
            Services::$name => 'service_name',
            Users::$name => 'user_name',
            Users::$surname => 'user_surname'
        ])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServiceSchedule::$table)
                ->on(OrdersService::$id, WorkersServiceSchedule::$order_id)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(WorkersServiceSchedule::$price_id, WorkersServicePricing::$id)
            ->innerJoin(Workers::$table)
                ->on(WorkersServicePricing::$worker_id, Workers::$id)
            ->leftJoin(Users::$table)
                ->on(OrdersService::$user_id, Users::$id)
            ->innerJoin(Affiliates::$table)
                ->on(OrdersService::$affiliate_id, Affiliates::$id)
            ->innerJoin(Services::$table)
            ->on(WorkersServicePricing::$service_id, Services::$id)
            ->whereEqual(OrdersService::$id, ':order_id', $id)
        ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $orderId
     * @return array|false
     *
     * [ user_id => can be null, email =>, ]
     */
    public function selectUserIdAndEmail(int $orderId): array | false
    {
        $this->builder->select([OrdersService::$user_id, OrdersService::$email])
            ->from(OrdersService::$table)
            ->whereEqual(OrdersService::$id, ':order_id', $orderId)
        ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ 'name' =>, 'start_datetime' => ]
     */
    public function selectStartDatetimeAndServiceName(int $id): array | false
    {
        $this->builder->select([OrdersService::$start_datetime, Services::$name])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(OrdersService::$price_id, WorkersServicePricing::$id)
            ->innerJoin(Services::$table)
                ->on(WorkersServicePricing::$service_id, Services::$id)
            ->whereEqual(OrdersService::$id, ':order_id', $id)
        ->build();

        return $this->db->singleRow();
    }

    /**
     * [
     *      0 => [id =>]
     *      1 => [id =>]
     * ......................
     * ]
     */
    public function selectAllUpcomingByPricingId(int $pricingId): array | false
    {
        $now = date('Y-m-d H:i:s');

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(OrdersService::$price_id, WorkersServicePricing::$id)
            ->whereEqual(WorkersServicePricing::$id, ':id', $pricingId)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start', $now)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * [
     *      0 => [ id => ]
     *      1 => [ id => ]
     * ........................
     * ]
     */
    public function selectAllUpcomingByServiceId(int $serviceId): array | false
    {
        $now = date('Y-m-d H:i:s');

        $this->builder->select([OrdersService::$id])
            ->from(OrdersService::$table)
            ->innerJoin(WorkersServicePricing::$table)
                ->on(OrdersService::$price_id, WorkersServicePricing::$id)
            ->whereEqual(WorkersServicePricing::$service_id, ':service_id', $serviceId)
            ->andGreaterEqual(OrdersService::$start_datetime, ':start', $now)
            ->andIsNull(OrdersService::$completed_datetime)
            ->andIsNull(OrdersService::$canceled_datetime)
        ->build();

        return $this->db->manyRows();
    }
}