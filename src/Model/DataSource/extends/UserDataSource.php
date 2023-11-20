<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
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
        $users = Users::$table;
        $_name = Users::$name;
        $_surname = Users::$surname;
        $_email = Users::$email;
        $_password = Users::$password;
        $_created_date = Users::$created_date;
        $_role_id = Users::$role_id;

        $roles = Roles::$table;
        $role_id = Roles::$id;
        $role_name = Roles::$name;

        $userRole = 'User';

        $this->db->query(
            "INSERT INTO $users ($_name, $_surname, $_password, $_email, $_created_date, $_role_id)
                VALUES (:name, :surname, :password, :email, NOW(), 
                        (SELECT $role_id FROM $roles WHERE $role_name = :user_role))"
        );
        $this->db->bind(':name', $user->getName());
        $this->db->bind(':surname', $user->getSurname());
        $this->db->bind(':password', $user->getPasswordHash());
        $this->db->bind(':email', $user->getEmail());

        $this->db->bind(':user_role', $userRole);

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserSetting(int $userId)
    {
        $usersSetting = UsersSetting::$table;
        $user_id = UsersSetting::$user_id;
        $this->db->query(
            "INSERT INTO $usersSetting ($user_id) VALUES (:user_id)"
        );
        $this->db->bind(':user_id', $userId);

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserSocial(int $userId)
    {
        $usersSocial = UsersSocial::$table;
        $user_id = UsersSocial::$user_id;

        $this->db->query(
            "INSERT INTO $usersSocial ($user_id) VALUES (:user_id)"
        );
        $this->db->bind(':user_id', $userId);

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserPhoto(int $userId)
    {
        $usersSocial = UsersPhoto::$table;
        $user_id = UsersPhoto::$user_id;

        $this->db->query(
            "INSERT INTO $usersSocial ($user_id) VALUES (:user_id)"
        );
        $this->db->bind(':user_id', $userId);

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
        $users = Users::$table;
        $name = Users::$name;
        $surname = Users::$surname;
        $email = Users::$email;

        $usersPhoto = UsersPhoto::$table;
        $filename = UsersPhoto::$name;

        $usersId = Users::$id;
        $usersPhotoUserId = UsersPhoto::$user_id;

        $this->db->query("
            SELECT $usersId, $name, $surname, $email, $filename
            FROM $users INNER JOIN $usersPhoto ON $usersId = $usersPhotoUserId
            WHERE $usersId = :id
        ");
        $this->db->bind(':id', $userId);

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
        $usersSocial = UsersSocial::$table;
        $usersSocialUserId = UsersSocial::$user_id;

        $this->db->query("
            SELECT * FROM $usersSocial 
            WHERE $usersSocialUserId = :id
        ");
        $this->db->bind(':id', $userId);
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
        $order_id = OrdersService::$id;
        $user_id = OrdersService::$user_id;
        $service_id = OrdersService::$service_id;
        $worker_id = OrdersService::$worker_id;
        $affiliate_id = OrdersService::$affiliate_id;
        $start_datetime = OrdersService::$start_datetime;
        $end_datetime = OrdersService::$end_datetime;
        $canceled = OrdersService::$canceled_datetime;
        $completed = OrdersService::$completed_datetime;

        $workersService = WorkersServicePricing::$table;
        $workersServiceWorkerId = WorkersServicePricing::$worker_id;
        $workersServiceServiceId = WorkersServicePricing::$service_id;
        $price = WorkersServicePricing::$price;
        $currency = WorkersServicePricing::$currency;

        $workers = Workers::$table;
        $workerId = Workers::$id;
        $worker_name = Workers::$name;
        $worker_surname = Workers::$surname;

        $affiliates = Affiliates::$table;
        $affiliateId = Affiliates::$id;
        $affiliate_city = Affiliates::$city;
        $affiliate_address = Affiliates::$address;

        $services = Services::$table;
        $serviceId = Services::$id;
        $service_name = Services::$name;

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
                AND '$start_datetime' <= '$now'
        ";

        $this->db->query("
            SELECT $order_id, $service_id as service_id, 
                   $service_name as service_name, $worker_id as worker_id, 
                   $worker_name as worker_name, 
                   $worker_surname as worker_surname, 
                   $affiliate_id as affiliate_id, 
                   $affiliate_city as affiliate_city, 
                   $affiliate_address as affiliate_address, 
                   $start_datetime, $end_datetime, $price, $currency
            FROM $ordersService 
                INNER JOIN $workers ON $worker_id = $workerId
                INNER JOIN $affiliates ON $affiliate_id = $affiliateId
                INNER JOIN $services ON $service_id = $serviceId
                INNER JOIN $workersService ON $worker_id = $workersServiceWorkerId
                                          AND $service_id = $workersServiceServiceId
            WHERE $user_id = :user_id
                AND $canceled IS NULL
                AND $completed IS NULL
                AND '$start_datetime' <= '$now'
            
            ORDER BY $orderByField $orderDirection
            LIMIT $limit
            OFFSET $offset
        ");
        $this->db->bind(':user_id', $userId);

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
        $departments = Departments::$table;

        $this->db->query("
            SELECT * FROM $departments
        ");

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
            $priceFrom, $priceTo, $schedule_worker_id, $schedule_service_id
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
        $departments = Departments::$table;
        $departments_id = Departments::$id;
        $departments_name = Departments::$name;

        $services = Services::$table;
        $services_id = Services::$id;
        $services_department_id = Services::$department_id;

        $this->db->query("
            SELECT $departments_id, $departments_name
                FROM $departments 
                INNER JOIN $services ON $services_department_id = $departments_id
            WHERE $services_id = :service_id
        ");

        $this->db->bind(':service_id', $serviceId);

        return $this->db->singleRow();
    }

    public function selectWorkerScheduleItemById(int $scheduleId) {
        $workerServiceSchedule = WorkersServiceSchedule::$table;
        $id = WorkersServiceSchedule::$id;

        $this->db->query("
            SELECT * FROM $workerServiceSchedule 
            WHERE $id = :schedule_id
        ");
        $this->db->bind(':schedule_id', $scheduleId);

        return $this->db->singleRow();
    }

    public function selectUserEmailById(int $userId) {
        $users = Users::$table;
        $id = Users::$id;
        $email = Users::$email;

        $this->db->query("
            SELECT $email FROM $users
            WHERE $id = :user_id
        ");
        $this->db->bind(':user_id', $userId);

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
        $ordersService = OrdersService::$table;
        $id = OrdersService::$id;
        $schedule_id = OrdersService::$schedule_id;

        $this->db->query("
            SELECT $id FROM $ordersService
            WHERE $schedule_id = :schedule_id
        ");
        $this->db->bind(':schedule_id', $scheduleId);

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
        $ordersService = OrdersService::$table;
        $user_id = OrdersService::$user_id;
        $schedule_id = OrdersService::$schedule_id;
        $email_column = OrdersService::$email;
        $service_id = OrdersService::$service_id;
        $worker_id = OrdersService::$worker_id;
        $affiliate_id = OrdersService::$affiliate_id;
        $start_datetime = OrdersService::$start_datetime;
        $end_datetime = OrdersService::$end_datetime;
        $created_datetime = OrdersService::$created_datetime;

        $this->db->query("
            INSERT INTO $ordersService 
                ($user_id, $schedule_id, $email_column, $service_id, $worker_id, 
                 $affiliate_id, $start_datetime, $end_datetime, $created_datetime)
            VALUES (:user_id, :schedule_id, :email, :service_id, 
                    :worker_id, :affiliate_id, :start, :end, NOW())
        ");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':schedule_id', $scheduleId);
        $this->db->bind(':email', $email);
        $this->db->bind(':service_id', $serviceId);
        $this->db->bind(':worker_id', $workerId);
        $this->db->bind(':affiliate_id', $affiliateId);
        $this->db->bind(':start', $startDatetime);
        $this->db->bind(':end', $endDatetime);

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function updateOrderIdInWorkersServiceSchedule(
        int $scheduleId, int $orderId
    ) {
        $workersServiceSchedule = WorkersServiceSchedule::$table;
        $id = WorkersServiceSchedule::$id;
        $order_id = WorkersServiceSchedule::$order_id;

        $this->db->query("
            UPDATE $workersServiceSchedule
            SET $order_id = :order_id
            WHERE $id = :schedule_id
        ");

        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':schedule_id', $scheduleId);

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}