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

        $this->db->query("
            SELECT $order_id, $service_id, $service_name, $worker_id, $worker_name, 
                   $worker_surname, $affiliate_id, $affiliate_city, $affiliate_address, 
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
            
            ORDER BY $orderByField $orderDirection
            LIMIT $limit
            OFFSET $offset
        ");
        $this->db->bind(':user_id', $userId);

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
        $departments = Departments::$table;

        $this->db->query("
            SELECT * FROM $departments
        ");

        return $this->db->manyRows();
    }

    private function _serviceFilter($serviceId, $columnToJoin)
    {
        $result = [
            'join' => '',
            'where' => ''
        ];
        $services = Services::$table;
        $services_id = Services::$id;

        if ($serviceId !== null && $serviceId !== '') {
            $result['join'] = " INNER JOIN $services ON $columnToJoin = $services_id ";
            $result['where'] = " AND $columnToJoin = $serviceId ";
        }
        return $result;
    }

    private function _workerFilter($workerId, $columnToJoin)
    {
        $result = [
            'join' => '',
            'where' => ''
        ];
        $workers = Workers::$table;
        $workers_id = Workers::$id;

        if ($workerId !== null && $workerId !== '') {
            $result['join'] = " INNER JOIN $workers ON $columnToJoin = $workers_id ";
            $result['where'] = " AND $columnToJoin = $workerId ";
        }
        return $result;
    }

    private function _affiliateFilter($affiliateId, $columnToJoin)
    {
        $result = [
            'join' => '',
            'where' => ''
        ];
        $affiliates = Affiliates::$table;
        $affiliates_id = Affiliates::$id;

        if ($affiliateId !== null && $affiliateId !== '') {
            $result['join'] = " INNER JOIN $affiliates ON $columnToJoin = $affiliates_id ";
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
            $result['where'] = " AND $schedule_day >= $dateFrom ";
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_day <= $dateTo ";
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
            $result['where'] = " AND $schedule_start_time >= $timeFrom ";
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_end_time <= $timeTo ";
        }
        return $result;
    }

    private function _priceFilter(
        $priceFrom, $priceTo, $columnToJoinWorker, $columnToJoinService
    )
    {
        $result = [
            'join' => '',
            'where' => ''
        ];
        $workersServicePricing = WorkersServicePricing::$table;
        $pricing_service_id = WorkersServicePricing::$service_id;
        $pricing_worker_id = WorkersServicePricing::$worker_id;
        $pricing_price = WorkersServicePricing::$price;

        $setFrom = ($priceFrom !== null && $priceFrom !== '');
        $setTo = ($priceTo !== null && $priceTo !== '');

        if($setFrom || $setTo) {
            $result['join'] = " INNER JOIN $workersServicePricing ON 
                                    $columnToJoinWorker = $pricing_worker_id
                                    AND $columnToJoinService = $pricing_service_id ";
        }
        if($setFrom) {
            $result['where'] = " AND $pricing_price >= $priceFrom ";
        }
        if($setTo) {
            $result['where'] .= " AND $pricing_price <= $priceTo ";
        }
        return $result;
    }

    private function _departmentFilter($departmentId, $columnToJoin) {
        $result = [
            'join' => '',
            'where' => ''
        ];
        $services = Services::$table;
        $services_id = Services::$id;
        $services_depId = Services::$department_id;

        $set = ($departmentId !== null && $departmentId !== '');
        if($set) {
            $result['join'] = " INNER JOIN $services ON $columnToJoin = $services_id ";
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

        $workers_id = Workers::$id;
        $workers_name = Workers::$name;
        $workers_surname = Workers::$surname;

        $affiliates_id = Affiliates::$id;
        $affiliates_city = Affiliates::$city;
        $affiliates_address = Affiliates::$address;

        $pricing_price = WorkersServicePricing::$price;
        $pricing_currency = WorkersServicePricing::$currency;

        $departmentFilter = $this->_departmentFilter($departmentId, $schedule_service_id);
        $serviceFilter = $this->_serviceFilter($serviceId, $schedule_service_id);
        $workerFilter = $this->_workerFilter($workerId, $schedule_worker_id);
        $affiliateFilter = $this->_affiliateFilter($affiliateId, $schedule_affiliate_id);
        $dateFilter = $this->_dateFilter($dateFrom, $dateTo);
        $timeFilter = $this->_timeFilter($timeFrom, $timeTo);
        $priceFilter = $this->_priceFilter(
            $priceFrom, $priceTo, $schedule_worker_id, $schedule_service_id
        );

        $this->db->query("
            SELECT $schedule_id as schedule_id, $services_id as service_id,
                   $services_serviceName as service_name,
                   $workers_id as worker_id, $workers_name as worker_name,
                   $workers_surname as worker_surname,
                   $affiliates_id as affiliate_id, $affiliates_city, $affiliates_address,
                   $schedule_day, 
                   $schedule_start_time, $schedule_end_time,
                   $pricing_price, $pricing_currency
            
            FROM $workerServiceSchedule 
                {$departmentFilter['join']}
                    
                {$workerFilter['join']}
                {$affiliateFilter['join']}
                {$priceFilter['join']}
            
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
}