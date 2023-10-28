<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\Table\Affiliates;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Roles;
use Src\Model\Table\Services;
use Src\Model\Table\Users;
use Src\Model\Table\UsersPhoto;
use Src\Model\Table\UsersSetting;
use Src\Model\Table\UsersSocial;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersService;

class UserDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function insertNewUser(string $name, string $surname, string $email, string $passwordHash)
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
        $this->db->bind(':name', $name);
        $this->db->bind(':surname', $surname);
        $this->db->bind(':password', $passwordHash);
        $this->db->bind(':email', $email);

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
     * @return array|false
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
            SELECT $userId, $name, $surname, $email, $filename
            FROM $users INNER JOIN $usersPhoto ON $usersId = $usersPhotoUserId
            WHERE $usersId = :id
        ");
        $this->db->bind(':id', $usersId);

        $result = $this->db->singleRow();
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * @param int $userId
     * @return array|false
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
        if($result) {
            return $result;
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
    public function selectUserComingAppointments(int $userId)
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

        $workersService = WorkersService::$table;
        $workersServiceWorkerId = WorkersService::$worker_id;
        $workersServiceServiceId = WorkersService::$service_id;
        $price = WorkersService::$price;
        $currency = WorkersService::$currency;

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
        ");
        $this->db->bind(':user_id', $userId);

        $result = $this->db->manyRows();
        if($result) {
            return $result;
        }
        return false;
    }
}