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
        $this->builder->insertInto(Users::$table,
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
        $this->builder->insertInto(UsersSetting::$table, [UsersSetting::$user_id])
                ->values([':user_id'], [$userId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserSocial(int $userId)
    {
        $this->builder->insertInto(UsersSocial::$table, [UsersSocial::$user_id])
                ->values([':user_id'], [$userId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertNewUserPhoto(int $userId)
    {
        $this->builder->insertInto(UsersPhoto::$table, [UsersPhoto::$user_id])
                ->values([':user_id'], [$userId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
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
        $this->builder->select(['*'])
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
        $this->builder->select(
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

    public function selectWorkerScheduleItemById(int $scheduleId) {
        $this->builder->select(['*'])
                ->from(WorkersServiceSchedule::$table)
                ->whereEqual(WorkersServiceSchedule::$id, ':schedule_id', $scheduleId)
            ->build();

        return $this->db->singleRow();
    }

    public function selectUserEmailById(int $userId) {
        $email = Users::$email;

        $this->builder->select([Users::$email])
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

    public function insertOrderService(
        ?int $scheduleId, ?int $userId, string $email, int $serviceId, int $workerId,
        int $affiliateId, string $startDatetime, string $endDatetime
    ) {
        $currentDatetime = date('Y-m-d H:i:s');
        $this->builder->insertInto(OrdersService::$table,
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
        $this->builder->update(WorkersServiceSchedule::$table)
                ->set(WorkersServiceSchedule::$order_id, ':order_id', $orderId)
                ->whereEqual(WorkersServiceSchedule::$id, ':schedule_id', $scheduleId)
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