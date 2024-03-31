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
        //$builder = new SqlBuilder($this->db);
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

        $setFrom = ($timeFrom !== null && $timeFrom !== '');
        $setTo = ($timeTo !== null && $timeTo !== '');

        if($setFrom) {
            $result['where'] = " AND $schedule_start_time >= '$timeFrom' ";
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

    public function updateServiceOrderCanceledDatetimeById(int $orderId) {
        $currentDatetime = date('Y-m-d H:i:s');
        //$builder = new SqlBuilder($this->db);
        $this->builder->update(OrdersService::$table)
            ->set(OrdersService::$canceled_datetime, ':canceled_datetime', $currentDatetime)
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

    public function updateCompletedDatetimeByOrderId(int $orderId) {
        $now = date('Y-m-d H:i:s');
        $this->builder->update(OrdersService::$table)
                    ->set(OrdersService::$completed_datetime, ':completed', $now)
                    ->whereEqual(OrdersService::$id, ':id', $orderId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

}