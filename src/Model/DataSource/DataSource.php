<?php

namespace Src\Model\DataSource;

use Src\DB\IDatabase;
use Src\Helper\Builder\IBuilder;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Departments;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Services;
use Src\Model\Table\Users;
use Src\Model\Table\UsersPhoto;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;

abstract class DataSource
{
    protected ?IDatabase $db = null;
    protected IBuilder $builder;

    public function __construct(IDatabase $db, IBuilder $builder = null)
    {
        if (!$this->db) {
            $this->db = $db;
        }
        $this->builder = $builder ?? new SqlBuilder($this->db);
    }

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->db->commitTransaction();
    }

    public function rollBackTransaction(): void
    {
        $this->db->rollBackTransaction();
    }

    protected function _getTotalRowsCountQuery(string $queryFrom) {
        $this->db->query(
            "SELECT COUNT(*) as totalRowsCount FROM $queryFrom"
        );

        $result = $this->db->singleRow();
        if($result) {
            return $result['totalRowsCount'];
        } else {
            return false;
        }
    }
    protected function _getTotalSumQuery(string $queryFrom, string $columnName)
    {
        $this->db->query(
            "SELECT SUM($columnName) as totalSum FROM $queryFrom"
        );

        $result = $this->db->singleRow();

        if ($result && isset($result['totalSum'])) {
            return $result['totalSum'];
        } else {
            return false;
        }
    }
    protected function _appendTotalRowsCount(string $queryFrom, array $result) {
        if($result) {
            $totalRowsCount = $this->_getTotalRowsCountQuery($queryFrom);
            if($totalRowsCount !== false) {
                $result += [
                    'totalRowsCount' => $totalRowsCount
                ];
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    protected function _appendTotalRowsSum(
        string $queryFrom, array $result, string $columnName
    ) {
        if($result) {
            $totalRowsCount = $this->_getTotalSumQuery($queryFrom, $columnName);
            if($totalRowsCount !== false) {
                $result += [
                    'totalSum' => $totalRowsCount
                ];
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function selectUserPasswordByEmail(string $email)
    {
        //$builder = new SqlBuilder($this->db);
        $this->builder->select([Users::$password])
                ->from(Users::$table)
                ->whereEqual(Users::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // users.password -> password
            $key = explode('.', Users::$password)[1];
            return $result[$key];
        }
        return false;
    }

    public function selectUserIdByEmail(string $email)
    {
//        $builder = new SqlBuilder($this->db);
        $this->builder->select([Users::$id])
                ->from(Users::$table)
                ->whereEqual(Users::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // users.id -> id
            $key = explode('.', Users::$id)[1];
            return $result[$key];
        }
        return false;
    }

    /**
     * @param int $userId
     * @return UserReadDto|false
     *
     * return = [
     *      'id' =>
     *      'name' =>
     *      'surname' =>
     *      'email' =>
     *      'filename' =>
     * ]
     *
     */
    public function selectUserInfoById(int $userId)
    {
        $this->builder->select(
                [Users::$id, Users::$name, Users::$surname,
                Users::$email, UsersPhoto::$name]
            )
            ->from(Users::$table)
            ->leftJoin(UsersPhoto::$table)
                ->on(Users::$id, UsersPhoto::$user_id)
            ->whereEqual(Users::$id, ':id', $userId)
        ->build();

        $result = $this->db->singleRow();
        if ($result) {
            return new UserReadDto($result);
        }
        return false;
    }

    /**
     * @param int $serviceId
     * @return array|false
     *
     * return = [
     *      0 => [
     *          'id' => ,
     *          'name' => ,
     *          'surname' => ,
     *      ]
     * .......
     * ]
     */
    public function selectWorkersForService(int $serviceId)
    {
        //$builder = new SqlBuilder($this->db);
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname])
                ->from(WorkersServicePricing::$table)
                ->innerJoin(Workers::$table)
                    ->on(WorkersServicePricing::$worker_id, Workers::$id)
                ->whereEqual(WorkersServicePricing::$service_id, ":id", $serviceId)
            ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $workerId
     * @return array|false
     *
     *  * return = [
     *      0 => [
     *          'id' => ,
     *          'name' => ,
     *      ]
     * .......
     * ]
     */
    public function selectServicesForWorker(int $workerId)
    {
        //$builder = new SqlBuilder($this->db);
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
     *
     * return = [
     *     0 => [
     *      'id' =>,
     *      'name' =>,
     *    ]
     * ........
     * ]
     */
    public function selectAllServices() {
        //$builder = new SqlBuilder($this->db);
        $this->builder->select([Services::$id, Services::$name])
                ->from(Services::$table)
            ->build();

        return $this->db->manyRows();
    }

    /**
     * @return array|false
     *
     * return = [
     *     0 => [
     *      'id' =>,
     *      'name' =>,
     *      'surname' =>
     *    ]
     * ........
     * ]
     */
    public function selectAllWorkers() {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname])
                ->from(Workers::$table)
            ->build();

        return $this->db->manyRows();
    }

    /**
     * @return array|false
     *
     * return = [
     *     0 => [
     *      'id' =>,
     *      'name' =>,
     *      'city' =>,
     *      'address' =>
     *    ]
     * ........
     * ]
     */
    public function selectAllAffiliates() {
     //$builder = New SqlBuilder($this->db);
        $this->builder->select([Affiliates::$id, Affiliates::$name,
                         Affiliates::$city, Affiliates::$address])
                ->from(Affiliates::$table)
            ->build();

        return $this->db->manyRows();
    }

    /**
     * @return array|false
     *
     * [
     *      0 => [
     *          'id' =>,
     *          'name' =>,
     *      ]
     *      ......
     * ]
     */
    public function selectAllDepartments()
    {
        //$builder = new SqlBuilder($this->db);
        $this->builder->select(['*'])
            ->from(Departments::$table)
            ->build();

        return $this->db->manyRows();
    }

    protected function _serviceFilter($serviceId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($serviceId !== null && $serviceId !== '') {
            $result['where'] = " AND $columnToJoin = $serviceId ";
        }
        return $result;
    }

    protected function _workerFilter($workerId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($workerId !== null && $workerId !== '') {
            $result['where'] = " AND $columnToJoin = $workerId ";
        }
        return $result;
    }

    protected function _userFilter($userId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($userId !== null && $userId !== '') {
            $result['where'] = " AND $columnToJoin = $userId ";
        }
        return $result;
    }

    protected function _statusFilter($status, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($status !== null && $status !== '') {
            $result['where'] = " AND $columnToJoin = $status ";
        }
        return $result;
    }

    protected function _affiliateFilter($affiliateId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($affiliateId !== null && $affiliateId !== '') {
            $result['where'] = " AND $columnToJoin = $affiliateId ";
        }
        return $result;
    }

    protected function _dateFilter($dateFrom, $dateTo)
    {
        $result = [
            'where' => ''
        ];
        $schedule_day = WorkersServiceSchedule::$day;

        $setFrom = ($dateFrom !== null && $dateFrom !== '');
        $setTo = ($dateTo !== null && $dateTo !== '');

        if($setFrom) {
            $result['where'] = " AND $schedule_day >= '$dateFrom' ";
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_day <= '$dateTo' ";
        }
        return $result;
    }

    protected function _timeFilter($timeFrom, $timeTo)
    {
        $result = [
            'where' => ''
        ];
        $schedule_start_time = WorkersServiceSchedule::$start_time;
        $schedule_end_time = WorkersServiceSchedule::$end_time;
        $schedule_day = WorkersServiceSchedule::$day;

        $setFrom = ($timeFrom !== null && $timeFrom !== '');
        $setTo = ($timeTo !== null && $timeTo !== '');

        if($setFrom) {
            $result['where'] = " AND $schedule_start_time >= '$timeFrom' ";
        } else {
            $result['where'] = " AND ($schedule_day > CURDATE() OR ($schedule_day = CURDATE() AND $schedule_start_time > CURTIME())) ";
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_end_time <= '$timeTo' ";
        }
        return $result;
    }

    protected function _priceFilter($priceFrom, $priceTo)
    {
        $result = [
            'where' => ''
        ];
        $pricing_price = WorkersServicePricing::$price;

        $setFrom = ($priceFrom !== null && $priceFrom !== '');
        $setTo = ($priceTo !== null && $priceTo !== '');

        if($setFrom) {
            $result['where'] = " AND $pricing_price >= '$priceFrom' ";
        }
        if($setTo) {
            $result['where'] .= " AND $pricing_price <= '$priceTo' ";
        }
        return $result;
    }

    protected function _departmentFilter($departmentId) {
        $result = [
            'where' => ''
        ];
        $services_depId = Services::$department_id;

        $set = ($departmentId !== null && $departmentId !== '');
        if($set) {
            $result['where'] = " AND $services_depId = $departmentId ";
        }
        return $result;
    }

    /**
     * @param $departmentId
     * @param $serviceId
     * @param $workerId
     * @param $affiliateId
     * @param $dateFrom
     * @param $dateTo
     * @param $timeFrom
     * @param $timeTo
     * @param $priceFrom
     * @param $priceTo
     * @return array|false
     *
     * response example
     * [
     *      0 => [
     *         'schedule_id' =>,
     *         'service_id' =>,
     *         'department_id' =>,
     *         'service_name' =>,
     *         'worker_id' =>,
     *         'worker_name' =>,
     *         'worker_surname' =>,
     *         'affiliate_id' =>,
     *         'city' =>,
     *         'address' =>,
     *         'day' =>,
     *         'start_time' =>,
     *         'end_time' =>,
     *         'price' =>,
     *         'currency' =>
     *      ]
     * ...............................
     * ]
     */
    public function selectSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null
    )
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

        $q = "SELECT $schedule_id as schedule_id, $services_id as service_id,
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
        ";
        //echo $q;
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

        return $this->db->manyRows();
    }

    /**
     * @param int $serviceId
     * @return array|false
     *
     * response example
     * [
     *      'id' =>,
     *      'nam'e' =>
     * ]
     */
    public function selectDepartmentByServiceId(int $serviceId) {
        //$builder = new SqlBuilder($this->db);
        $this->builder->select([Departments::$id, Departments::$name])
            ->from(Departments::$table)
            ->innerJoin(Services::$table)
            ->on(Departments::$id, Services::$department_id)
            ->whereEqual(Services::$id, ':service_id', $serviceId)
            ->build();

        return $this->db->singleRow();
    }

    public function updateServiceOrderCanceledDatetimeById(int $orderId)
    {
        $currentDatetime = date('Y-m-d H:i:s');
        $canceled = -1;

        $this->builder->update(OrdersService::$table)
            ->set(OrdersService::$canceled_datetime, ':canceled_datetime', $currentDatetime)
            ->andSet(OrdersService::$status, ':status', $canceled)
            ->whereEqual(OrdersService::$id, ':order_id', $orderId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateOrderIdByScheduleId(int $scheduleId) {
        //$builder = new SqlBuilder($this->db);
        $this->builder->update(WorkersServiceSchedule::$table)
            ->setNull(WorkersServiceSchedule::$order_id)
            ->whereEqual(WorkersServiceSchedule::$id, ':id', $scheduleId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateCompletedDatetimeByOrderId(int $orderId)
    {
        $now = date('Y-m-d H:i:s');
        $completed = 1;

        $this->builder->update(OrdersService::$table)
                    ->set(OrdersService::$completed_datetime, ':completed', $now)
                    ->andSet(OrdersService::$status, ':status', $completed)
                    ->whereEqual(OrdersService::$id, ':id', $orderId)
                    ->andLess(OrdersService::$start_datetime, ':start', $now)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectOrders(
        $limit, $offset,
        $orderField = 'orders_service.id', $orderDirection = 'asc',
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $priceFrom = null, $priceTo = null,
        $userId = null, $status = null
    ) {
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
                $orderField $orderDirection
            
            LIMIT 
                $limit
            OFFSET 
                $offset;
            ");

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        $result = $this->_appendTotalRowsCount($queryFrom, $result);
        if($result) {
            return $this->_appendTotalRowsSum($queryFrom, $result, WorkersServicePricing::$price);
        }
        return false;
    }

    public function updateCompletedDatetimeByOrderIds(array $ids)
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

    public function deleteOrdersByIds(array $ids)
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

    public function updateCanceledDatetimeByOrderIds(array $ids)
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

    public function selectScheduleIdByOrderId(int $orderId) {
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
}