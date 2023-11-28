<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\DTO\Read\UserSocialReadDto;
use Src\Model\DTO\Write\UserWriteDto;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Departments;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Roles;
use Src\Model\Table\Services;
use Src\Model\Table\Users;
use Src\Model\Table\UsersPhoto;
use Src\Model\Table\UsersSetting;
use Src\Model\Table\UsersSocial;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersAffiliate;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;

class UserDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function insertNewUser(UserWriteDto $user)
    {
        $userRole = \Src\Model\Entity\Roles::$USER;

        $currentDatetime = date('Y-m-d H:i:s');
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(Users::$table,
                    [
                        Users::$name, Users::$surname, Users::$password, Users::$email,
                        Users::$created_date, Users::$role_id
                    ]
                )->values(
                    [':name', ':surname', ':password', ':email', ':created_date'],
                    [$user->getName(), $user->getSurname(), $user->getPasswordHash(),
                     $user->getEmail(), $currentDatetime],
                    true
                )->subqueryBegin()
                    ->select([Roles::$id])
                    ->from(Roles::$table)
                    ->whereEqual(Roles::$name, ':user_role', $userRole)
                 ->subqueryEnd()
                ->queryEnd()
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserSetting(int $userId)
    {
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(UsersSetting::$table, [UsersSetting::$user_id])
                ->values([':user_id'], [$userId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserSocial(int $userId)
    {
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(UsersSocial::$table, [UsersSocial::$user_id])
                ->values([':user_id'], [$userId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserPhoto(int $userId)
    {
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(UsersPhoto::$table, [UsersPhoto::$user_id])
                ->values([':user_id'], [$userId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
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
        $builder = new SqlBuilder($this->db);
        $builder->select([Users::$id, Users::$name, Users::$surname, Users::$email, UsersPhoto::$name])
                ->from(Users::$table)
                ->innerJoin(UsersPhoto::$table)
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
     * @param int $userId
     * @return UserSocialReadDto|false
     *
     * return = [
     *      'id' =>
     *      'user_id' =>
     *      'Instagram' =>
     *      'TikTok' =>
     *      'Facebook' =>
     *      'YouTube' =>
     *      'Google' =>
     * ]
     */
    public function selectUserSocialById(int $userId)
    {
        $builder = new SqlBuilder($this->db);
        $builder->select(['*'])
                ->from(UsersSocial::$table)
                ->whereEqual(UsersSocial::$user_id, ':id', $userId)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            return new UserSocialReadDto($result);
        }
        return false;
    }

    /**
     * @param int $userId
     * @return array|false
     *
     * return = [
     *    'id' =>
     *    'service_id' =>
     *    'service_name' =>
     *    'worker_id' =>
     *    'worker_name' =>
     *    'worker_surname' =>
     *    'affiliate_id' =>
     *    'affiliate_city' =>
     *    'affiliate_address' =>
     *    'start_datetime' =>
     *    'end_datetime' =>
     *    'price' =>
     *    'currency' =>
     * ]
     */
    public function selectUserComingAppointments(
        int    $userId, int $limit, int $offset,
        string $orderByField = 'orders_service.id', string $orderDirection = 'asc')
    {
        $ordersService = OrdersService::$table;
        $user_id = OrdersService::$user_id;
        $service_id = OrdersService::$service_id;
        $worker_id = OrdersService::$worker_id;
        $affiliate_id = OrdersService::$affiliate_id;
        $start_datetime = OrdersService::$start_datetime;
        $canceled = OrdersService::$canceled_datetime;
        $completed = OrdersService::$completed_datetime;

        $workersService = WorkersServicePricing::$table;
        $workersServiceWorkerId = WorkersServicePricing::$worker_id;
        $workersServiceServiceId = WorkersServicePricing::$service_id;

        $workers = Workers::$table;
        $workerId = Workers::$id;

        $affiliates = Affiliates::$table;
        $affiliateId = Affiliates::$id;

        $services = Services::$table;
        $serviceId = Services::$id;

        $now = date("Y-m-d H:i:s", time());

        $queryFrom = "
            $ordersService 
                INNER JOIN $workers ON $worker_id = $workerId
                INNER JOIN $affiliates ON $affiliate_id = $affiliateId
                INNER JOIN $services ON $service_id = $serviceId
                INNER JOIN $workersService ON $worker_id = $workersServiceWorkerId
                                          AND $service_id = $workersServiceServiceId
            WHERE $user_id = $userId
                AND $canceled IS NULL
                AND $completed IS NULL
                AND $start_datetime >= '$now'
        ";

        $currentDatetime = date('Y-m-d H:i:s');
        $builder = new SqlBuilder($this->db);
        $builder->select(
                [OrdersService::$id, OrdersService::$service_id, Services::$name, OrdersService::$worker_id,
                Workers::$name, Workers::$surname, OrdersService::$affiliate_id, Affiliates::$city,
                Affiliates::$address, OrdersService::$start_datetime, OrdersService::$end_datetime,
                WorkersServicePricing::$price, WorkersServicePricing::$currency],
                [
                    OrdersService::$service_id => 'service_id', Services::$name => 'service_name',
                    OrdersService::$worker_id => 'worker_id', Workers::$name => 'worker_name',
                    Workers::$surname => 'worker_surname', OrdersService::$affiliate_id => 'affiliate_id',
                    Affiliates::$city => 'affiliate_city', Affiliates::$address => 'affiliate_address'
                ]
            )
            ->from(OrdersService::$table)
                ->innerJoin(Workers::$table)
                    ->on(OrdersService::$worker_id, Workers::$id)
                ->innerJoin(Affiliates::$table)
                    ->on(OrdersService::$affiliate_id, Affiliates::$id)
                ->innerJoin(Services::$table)
                    ->on(OrdersService::$service_id, Services::$id)
                ->innerJoin(WorkersServicePricing::$table)
                    ->on(OrdersService::$worker_id, WorkersServicePricing::$worker_id)
                    ->andOn(OrdersService::$service_id, WorkersServicePricing::$service_id)
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

        return $this->_appendTotalRowsCount($queryFrom, $result);
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
        $builder = new SqlBuilder($this->db);
        $builder->select(['*'])
                ->from(Departments::$table)
            ->build();

        return $this->db->manyRows();
    }

    private function _serviceFilter($serviceId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($serviceId !== null && $serviceId !== '') {
            $result['where'] = " AND $columnToJoin = $serviceId ";
        }
        return $result;
    }

    private function _workerFilter($workerId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($workerId !== null && $workerId !== '') {
            $result['where'] = " AND $columnToJoin = $workerId ";
        }
        return $result;
    }

    private function _affiliateFilter($affiliateId, $columnToJoin)
    {
        $result = [
            'where' => ''
        ];

        if ($affiliateId !== null && $affiliateId !== '') {
            $result['where'] = " AND $columnToJoin = $affiliateId ";
        }
        return $result;
    }

    private function _dateFilter($dateFrom, $dateTo)
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

    private function _timeFilter($timeFrom, $timeTo)
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

    private function _priceFilter($priceFrom, $priceTo)
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

    private function _departmentFilter($departmentId) {
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
        $workers_name = Workers::$name;
        $workers_surname = Workers::$surname;

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
                   $workers_id as worker_id, $workers_name as worker_name,
                   $workers_surname as worker_surname,
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
                   $workers_id as worker_id, $workers_name as worker_name,
                   $workers_surname as worker_surname,
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
        $builder = new SqlBuilder($this->db);
        $builder->select([Departments::$id, Departments::$name])
                ->from(Departments::$table)
                ->innerJoin(Services::$table)
                    ->on(Departments::$id, Services::$department_id)
                ->whereEqual(Services::$id, ':service_id', $serviceId)
            ->build();

        return $this->db->singleRow();
    }

    public function selectWorkerScheduleItemById(int $scheduleId) {
        $builder = new SqlBuilder($this->db);
        $builder->select(['*'])
                ->from(WorkersServiceSchedule::$table)
                ->whereEqual(WorkersServiceSchedule::$id, ':schedule_id', $scheduleId)
            ->build();

        return $this->db->singleRow();
    }

    public function selectUserEmailById(int $userId) {
        $email = Users::$email;

        $builder = new SqlBuilder($this->db);
        $builder->select([Users::$email])
                ->from(Users::$table)
                ->whereEqual(Users::$id, ':user_id', $userId)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            /**
             * users.email -> email
             */
            $emailColumn = explode('.', $email)[1];
            return $result[$emailColumn];
        }
        return $result;
    }

    public function selectOrderServiceByScheduleId(int $scheduleId) {
        $id = OrdersService::$id;

        $builder = new SqlBuilder($this->db);
        $builder->select([OrdersService::$id])
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

    public function insertOrderService(
        ?int $scheduleId, ?int $userId, string $email, int $serviceId, int $workerId,
        int $affiliateId, string $startDatetime, string $endDatetime
    ) {
        $currentDatetime = date('Y-m-d H:i:s');
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(OrdersService::$table,
                    [
                        OrdersService::$user_id, OrdersService::$schedule_id,
                        OrdersService::$email, OrdersService::$service_id,
                        OrdersService::$worker_id, OrdersService::$affiliate_id,
                        OrdersService::$start_datetime, OrdersService::$end_datetime,
                        OrdersService::$created_datetime
                    ]
                )
                ->values(
                    [':user_id', ':schedule_id', ':email', ':service_id', ':worker_id',
                     ':affiliate_id', ':start', ':end', ':created_datetime'],
                    [$userId, $scheduleId, $email, $serviceId, $workerId,
                     $affiliateId, $startDatetime, $endDatetime, $currentDatetime]
                )
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function updateOrderIdInWorkersServiceSchedule(
        int $scheduleId, int $orderId
    ) {
        $builder = new SqlBuilder($this->db);
        $builder->update(WorkersServiceSchedule::$table)
                ->set(WorkersServiceSchedule::$order_id, ':order_id', $orderId)
                ->whereEqual(WorkersServiceSchedule::$id, ':schedule_id', $scheduleId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateServiceOrderCanceledDatetimeById(int $orderId) {
        $currentDatetime = date('Y-m-d H:i:s');
        $builder = new SqlBuilder($this->db);
        $builder->update(OrdersService::$table)
                ->set(OrdersService::$canceled_datetime, ':canceled_datetime', $currentDatetime)
                ->whereEqual(OrdersService::$id, ':order_id', $orderId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
    public function selectScheduleIdByOrderId(int $orderId) {
        $builder = new SqlBuilder($this->db);
        $builder->select([OrdersService::$schedule_id])
                ->from(OrdersService::$table)
                ->whereEqual(OrdersService::$id, ':id', $orderId)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            return $result[explode('.', OrdersService::$schedule_id)[1]];
        }
        return $result;
    }
    public function updateOrderIdByScheduleId(int $scheduleId) {
        $builder = new SqlBuilder($this->db);
        $builder->update(WorkersServiceSchedule::$table)
                ->setNull(WorkersServiceSchedule::$order_id)
                ->whereEqual(WorkersServiceSchedule::$id, ':id', $scheduleId)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}