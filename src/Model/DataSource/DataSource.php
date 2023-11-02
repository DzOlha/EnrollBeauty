<?php

namespace Src\Model\DataSource;

use Src\DB\IDatabase;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Roles;
use Src\Model\Table\Services;
use Src\Model\Table\Users;
use Src\Model\Table\UsersPhoto;
use Src\Model\Table\UsersSetting;
use Src\Model\Table\UsersSocial;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;

abstract class DataSource
{
    protected ?IDatabase $db = null;

    public function __construct(IDatabase $db)
    {
        if (!$this->db) {
            $this->db = $db;
        }
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

    public function selectUserPasswordByEmail(string $email)
    {
        $users = Users::$table;
        $_email = Users::$email;
        $_password = Users::$password;

        $this->db->query(
            "SELECT $_password FROM $users WHERE $_email = :email"
        );

        $this->db->bind(':email', $email);

        $result = $this->db->singleRow();
        if ($result) {
            // users.password -> password
            $key = explode('.', $_password)[1];
            return $result[$key];
        }
        return false;
    }

    public function selectUserIdByEmail(string $email)
    {
        $users = Users::$table;
        $_email = Users::$email;
        $_id = Users::$id;

        $this->db->query(
            "SELECT $_id FROM $users WHERE $_email = :email"
        );

        $this->db->bind(':email', $email);

        $result = $this->db->singleRow();
        if ($result) {
            // users.password -> password
            $key = explode('.', $_id)[1];
            return $result[$key];
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
        $workersServicePricing = WorkersServicePricing::$table;
        $service_id = WorkersServicePricing::$service_id;
        $worker_id = WorkersServicePricing::$worker_id;

        $workers = Workers::$table;
        $workerId = Workers::$id;
        $workerName = Workers::$name;
        $workerSurname = Workers::$surname;

        $this->db->query("
            SELECT $workerId, $workerName, $workerSurname
            FROM $workersServicePricing 
                INNER JOIN $workers ON $worker_id = $workerId
            WHERE $service_id = :id
        ");
        $this->db->bind(':id', $serviceId);

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
        $workersServicePricing = WorkersServicePricing::$table;
        $service_id = WorkersServicePricing::$service_id;
        $worker_id = WorkersServicePricing::$worker_id;

        $services = Services::$table;
        $serviceId = Services::$id;
        $serviceName = Services::$name;

        $this->db->query("
            SELECT $serviceId, $serviceName
            FROM $workersServicePricing 
                INNER JOIN $services ON $service_id = $serviceId
            WHERE $worker_id = :id
        ");
        $this->db->bind(':id', $workerId);

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
        $services = Services::$table;
        $service_id = Services::$id;
        $service_name = Services::$name;

        $this->db->query("
            SELECT $service_id, $service_name FROM $services
        ");
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
        $workers = Workers::$table;
        $worker_id = Workers::$id;
        $worker_name = Workers::$name;
        $worker_surname = Workers::$surname;

        $this->db->query("
            SELECT $worker_id, $worker_name, $worker_surname
            FROM $workers
        ");
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
        $affiliates = Affiliates::$table;
        $affiliate_id = Affiliates::$id;
        $affiliate_name = Affiliates::$name;
        $affiliate_city = Affiliates::$city;
        $affiliate_address = Affiliates::$address;

        $this->db->query("
            SELECT $affiliate_id, $affiliate_name, $affiliate_city, $affiliate_address
            FROM $affiliates
        ");
        return $this->db->manyRows();
    }
}