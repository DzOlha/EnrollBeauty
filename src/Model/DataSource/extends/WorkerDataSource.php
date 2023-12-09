<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\WorkerWriteDTO;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Departments;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Services;
use Src\Model\Table\Users;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;
use Src\Model\Table\WorkersSetting;
use Src\Model\Table\WorkersSocial;
use function Symfony\Component\String\b;


class WorkerDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectWorkerByEmail(string $email)
    {
        $id = Workers::$id;

        $this->builder->select([Workers::$id])
            ->from(Workers::$table)
            ->whereEqual(Workers::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            /**
             * workers.id -> id
             */
            return $result[explode('.', $id)[1]];
        }
        return false;
    }

    public function insertWorker(WorkerWriteDTO $worker)
    {
        $currentDatetime = date('Y-m-d H:i:s');

        $this->builder->insertInto(Workers::$table,
            [
                Workers::$name, Workers::$surname, Workers::$password, Workers::$email,
                Workers::$gender, Workers::$age, Workers::$years_of_experience,
                Workers::$position_id, Workers::$salary, Workers::$role_id,
                Workers::$created_date
            ]
        )->values(
            [':name', ':surname', ':password', ':email',
             ':gender', ':age', ':experience', ':position_id',
             ':salary', ':role_id', ':created_date'],
            [
                $worker->getName(), $worker->getSurname(), $worker->getPassword(),
                $worker->getEmail(), $worker->getGender(), $worker->getAge(),
                $worker->getYearsOfExperience(), $worker->getPositionId(),
                $worker->getSalary(), $worker->getRoleId(), $currentDatetime
            ]
        )->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertWorkerSettings(int $workerId, string $recoveryCode)
    {
        $this->builder->insertInto(WorkersSetting::$table,
            [
                WorkersSetting::$worker_id,
                WorkersSetting::$recovery_code
            ]
        )->values([':worker_id', ':recovery_code'], [$workerId, $recoveryCode])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertWorkerSocial(int $workerId)
    {
        $this->builder->insertInto(WorkersSocial::$table, [WorkersSocial::$worker_id])
            ->values([':worker_id'], [$workerId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateWorkerSettingDateOfSendingRecoveryCode(
        int $id, string $recoveryCode
    )
    {
        $currentDateTime = date("Y-m-d H:i:s");

        $this->builder->update(WorkersSetting::$table)
            ->set(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
            ->andSet(WorkersSetting::$date_of_sending, ':date_of_sending', $currentDateTime)
            ->whereEqual(WorkersSetting::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectWorkerDateSendingByRecoveryCode(
        string $recoveryCode
    )
    {
        $this->builder->select([WorkersSetting::$date_of_sending])
            ->from(WorkersSetting::$table)
            ->whereEqual(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            return $result[explode('.', WorkersSetting::$date_of_sending)[1]];
        } else {
            return false;
        }
    }

    public function updateWorkerPasswordByRecoveryCode(
        string $recoveryCode, string $passwordHash
    )
    {
        $this->builder->update(Workers::$table)
            ->innerJoin(WorkersSetting::$table)
            ->on(Workers::$id, WorkersSetting::$worker_id)
            ->set(Workers::$password, ':password', $passwordHash)
            ->whereEqual(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectWorkerPasswordByEmail(string $email)
    {
        $this->builder->select([Workers::$password])
            ->from(Workers::$table)
            ->whereEqual(Workers::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // workers.password -> password
            return $result[explode('.', Workers::$password)[1]];
        } else {
            return false;
        }
    }

    public function selectWorkerIdByEmail(string $email)
    {
        $this->builder->select([Workers::$id])
            ->from(Workers::$table)
            ->whereEqual(Workers::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // workers.id -> id
            return $result[explode('.', Workers::$id)[1]];
        } else {
            return false;
        }
    }

    public function updateRecoveryCodeByRecoveryCode(string $recoveryCode)
    {
        $this->builder->update(WorkersSetting::$table)
            ->setNull(WorkersSetting::$recovery_code)
            ->whereEqual(
                WorkersSetting::$recovery_code,
                ':recovery_code',
                $recoveryCode
            )
            ->build();
        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectWorkerInfoById(int $workerId)
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname, Workers::$email])
            ->from(Workers::$table)
            ->whereEqual(Workers::$id, ':id', $workerId)
            ->build();

        return $this->db->singleRow();
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
     *                  'order_id' =>,
     *         'service_id' =>,
     *         'department_id' =>,
     *         'service_name' =>,
     *                  'user_id' =>,
     *                  'user_email' =>,
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
    public function selectWorkerOrderedSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null,
    )
    {
        $workerServiceSchedule = WorkersServiceSchedule::$table;
        $schedule_id = WorkersServiceSchedule::$id;
        $schedule_service_id = WorkersServiceSchedule::$service_id;
        $schedule_worker_id = WorkersServiceSchedule::$worker_id;
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
        $pricing_service_id = WorkersServicePricing::$service_id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;
        $pricing_price = WorkersServicePricing::$price;
        $pricing_currency = WorkersServicePricing::$currency;

        $departmentFilter = $this->_departmentFilter($departmentId);
        $serviceFilter = $this->_serviceFilter($serviceId, $schedule_service_id);
        $workerFilter = $this->_workerFilter($workerId, $schedule_worker_id);
        $affiliateFilter = $this->_affiliateFilter($affiliateId, $schedule_affiliate_id);
        $dateFilter = $this->_dateFilter($dateFrom, $dateTo);
        $timeFilter = $this->_timeFilter($timeFrom, $timeTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo
        );

        $q = "SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $ordersOrderId as order_id, $ordersUserId as user_id, $ordersUserEmail as user_email,
                   $services_departmentId as department_id, 
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $services ON $schedule_service_id = $services_id
                INNER JOIN $workers ON $schedule_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $orders ON $schedule_order_id = $ordersOrderId
                INNER JOIN $workersServicePricing 
                    ON $schedule_worker_id = $pricing_worker_id
                    AND $schedule_service_id = $pricing_service_id
            
            WHERE $schedule_order_id IS NOT NULL AND $completedTime IS NULL
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
                   $ordersOrderId as order_id, $ordersUserId as user_id, $ordersUserEmail as user_email,
                   $services_departmentId as department_id,
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $services ON $schedule_service_id = $services_id
                INNER JOIN $workers ON $schedule_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $orders ON $schedule_order_id = $ordersOrderId
                INNER JOIN $workersServicePricing 
                    ON $schedule_worker_id = $pricing_worker_id
                    AND $schedule_service_id = $pricing_service_id
            
            WHERE $schedule_order_id IS NOT NULL AND $completedTime IS NULL
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


    public function selectWorkerFreeSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null,
    )
    {
        $workerServiceSchedule = WorkersServiceSchedule::$table;
        $schedule_id = WorkersServiceSchedule::$id;
        $schedule_service_id = WorkersServiceSchedule::$service_id;
        $schedule_worker_id = WorkersServiceSchedule::$worker_id;
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
        $pricing_service_id = WorkersServicePricing::$service_id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;
        $pricing_price = WorkersServicePricing::$price;
        $pricing_currency = WorkersServicePricing::$currency;

        $departmentFilter = $this->_departmentFilter($departmentId);
        $serviceFilter = $this->_serviceFilter($serviceId, $schedule_service_id);
        $workerFilter = $this->_workerFilter($workerId, $schedule_worker_id);
        $affiliateFilter = $this->_affiliateFilter($affiliateId, $schedule_affiliate_id);
        $dateFilter = $this->_dateFilter($dateFrom, $dateTo);
        $timeFilter = $this->_timeFilter($timeFrom, $timeTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo
        );

        $q = "SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $services_departmentId as department_id, 
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $services ON $schedule_service_id = $services_id
                INNER JOIN $workers ON $schedule_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $workersServicePricing 
                    ON $schedule_worker_id = $pricing_worker_id
                    AND $schedule_service_id = $pricing_service_id
            
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
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                INNER JOIN $services ON $schedule_service_id = $services_id
                INNER JOIN $workers ON $schedule_worker_id = $workers_id 
                INNER JOIN $affiliates ON $schedule_affiliate_id = $affiliates_id
                INNER JOIN $workersServicePricing 
                    ON $schedule_worker_id = $pricing_worker_id
                    AND $schedule_service_id = $pricing_service_id
            
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
    public function selectDepartmentsForWorker(int $workerId)
    {
        $this->builder->select([Departments::$id, Departments::$name])
            ->from(Departments::$table)
            ->innerJoin(Services::$table)
            ->on(Departments::$id, Services::$department_id)
            ->innerJoin(WorkersServicePricing::$table)
            ->on(Services::$id, WorkersServicePricing::$service_id)
            ->whereEqual(WorkersServicePricing::$worker_id, ':worker_id', $workerId)
            ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $orderId
     * @return array|false
     *
     * [
     *  'user_id' =>
     *  'email' =>
     * ]
     */
    public function selectUserByOrderId(int $orderId)
    {
        $this->builder->select([OrdersService::$user_id, OrdersService::$email])
            ->from(OrdersService::$table)
            ->whereEqual(OrdersService::$id, ':order_id', $orderId)
            ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $orderId
     * @return array|false
     *
     * [
     *      'name' =>
     *      'start_datetime' =>
     * ]
     */
    public function selectOrderDetails(int $orderId)
    {
        $this->builder->select([OrdersService::$start_datetime, Services::$name])
            ->from(OrdersService::$table)
            ->innerJoin(Services::$table)
            ->on(OrdersService::$service_id, Services::$id)
            ->whereEqual(OrdersService::$id, ':order_id', $orderId)
            ->build();

        return $this->db->singleRow();
    }

    public function selectWorkerServicePricingByIds(int $workerId, $serviceId)
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

    public function insertWorkerServicePricing(
        int $workerId, int $serviceId, $price
    )
    {
        $this->builder->insertInto(WorkersServicePricing::$table, [
            WorkersServicePricing::$worker_id, WorkersServicePricing::$service_id,
            WorkersServicePricing::$price
        ])
            ->values([':worker_id', ':service_id', ':price'],
                [$workerId, $serviceId, $price])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param int $workerId
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false
     *
     * [
     * 0 => [
     *      'id' =>
     *      'name' => service name
     *      'service_id' =>
     *      'price' =>
     *      'currency' =>
     *      'updated_datetime' =>
     *  ]
     * ......
     *  'totalRowsCount':
     * ]
     */
    public function selectAllWorkersServicePricing(
        int    $workerId, int $limit, int $offset,
        string $orderByField = 'workers_service_pricing.id', string $orderDirection = 'asc'
    )
    {
        $pricingTable = WorkersServicePricing::$table;
        $servicesTable = Services::$table;

        $worker_id = WorkersServicePricing::$worker_id;

        $service_id = Services::$id;
        $pricing_service_id = WorkersServicePricing::$service_id;

        $queryFrom = "
            $pricingTable INNER JOIN $servicesTable ON $pricing_service_id = $service_id
            WHERE $worker_id = $workerId
        ";

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
        return $this->_appendTotalRowsCount($queryFrom, $result);
    }

    /**
     * @param int $workerId
     * @param string $day
     * @return array|false
     *
     *  [
     *       0 => [
     *           'start_time' =>
     *           'end_time' =>
     *       ]
     *   ........................
     *  ]
     */
    public function selectFilledTimeIntervalsByWorkerIdAndDay(
        int $workerId, string $day
    )
    {
        $this->builder->select([WorkersServiceSchedule::$start_time,
                                WorkersServiceSchedule::$end_time])
            ->from(WorkersServiceSchedule::$table)
            ->whereEqual(WorkersServiceSchedule::$worker_id, ':worker_id', $workerId)
            ->andEqual(WorkersServiceSchedule::$day, ':day', $day)
            ->build();

        return $this->db->manyRows();
    }

    public function selectScheduleForWorkerByDayAndTime(
        int $workerId, string $day, string $startTime, string $endTime
    )
    {
        $schedule = WorkersServiceSchedule::$table;
        $start_time = WorkersServiceSchedule::$start_time;
        $end_time = WorkersServiceSchedule::$end_time;
        $day_column = WorkersServiceSchedule::$day;
        $worker_id = WorkersServiceSchedule::$worker_id;
        $id = WorkersServiceSchedule::$id;

        $q = "
            SELECT $id FROM $schedule
            WHERE $worker_id = :worker_id
            AND $day_column = :day
            AND (
                 ($start_time <= :start_time AND $start_time < :end_time 
                    AND $end_time > :start_time AND $end_time >= :end_time)
                
                OR ($start_time <= :start_time AND $start_time < :end_time 
                    AND $end_time > :start_time AND $end_time <= :end_time)
                
                OR ($start_time >= :start_time AND  $start_time < :end_time
                    AND $end_time > :start_time AND $end_time >= :end_time)
            )
        ";
        //echo $q;

        $this->db->query("
            SELECT $id FROM $schedule
            WHERE $worker_id = :worker_id
            AND $day_column = :day
            AND (
                 ($start_time <= :start_time AND $start_time < :end_time 
                    AND $end_time > :start_time AND $end_time >= :end_time)
                
                OR ($start_time <= :start_time AND $start_time < :end_time 
                    AND $end_time > :start_time AND $end_time <= :end_time)
                
                OR ($start_time >= :start_time AND  $start_time < :end_time
                    AND $end_time > :start_time AND $end_time >= :end_time)
                
                OR ($start_time >= :start_time AND $end_time <= :end_time)
            )
        ");
        $this->db->bind(':worker_id', $workerId);
        $this->db->bind(':day', $day);
        $this->db->bind(':start_time', $startTime);
        $this->db->bind(':end_time', $endTime);

        return $this->db->manyRows();
    }

    public function insertWorkerServiceSchedule(
        int    $workerId, int $serviceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    )
    {
        $this->builder->insertInto(WorkersServiceSchedule::$table,
            [
                WorkersServiceSchedule::$worker_id, WorkersServiceSchedule::$service_id,
                WorkersServiceSchedule::$affiliate_id, WorkersServiceSchedule::$day,
                WorkersServiceSchedule::$start_time, WorkersServiceSchedule::$end_time
            ])
            ->values(
                [':worker_id', ':service_id', ':affiliate_id',
                 ':day', ':start_time', ':end_time'],
                [$workerId, $serviceId, $affiliateId, $day, $startTime, $endTime]
            )
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectAllServicesWithDepartments(
        int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    )
    {
        $services = Services::$table;
        $servicesDepartmentId = Services::$department_id;

        $departments = Departments::$table;
        $departmentsId = Departments::$id;
        $departmentsName = Departments::$name;

        $queryFrom = "
            $services INNER JOIN $departments ON $servicesDepartmentId = $departmentsId
        ";
        $this->builder->select([Services::$id, Services::$name,
                                "$departmentsName as department_name",
                                "$departmentsId as department_id"])
            ->from(Services::$table)
            ->innerJoin(Departments::$table)
            ->on(Services::$department_id, $departmentsId)
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

    public function selectServiceIdByNameAndDepartmentId(
        string $serviceName, int $departmentId
    ) {
        $this->builder->select([Services::$id])
                    ->from(Services::$table)
                    ->whereEqual(Services::$name, ':name', $serviceName)
                    ->andEqual(Services::$department_id, ':department_id', $departmentId)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            // services.id -> id
            return $result[explode('.', Services::$id)[1]];
        }
    }

    public function insertNewService(string $serviceName, int $departmentId)
    {
        $this->builder->insertInto(Services::$table, [Services::$name, Services::$department_id])
                    ->values([':name', ":department_id"], [$serviceName, $departmentId])
                ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function updateWorkerServicePricing(int $workerId, int $serviceId, $price)
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
}