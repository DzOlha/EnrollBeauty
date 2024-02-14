<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\Table\Admins;
use Src\Model\Table\AdminsSetting;
use Src\Model\Table\Affiliates;
use Src\Model\Table\Departments;
use Src\Model\Table\OrdersService;
use Src\Model\Table\Positions;
use Src\Model\Table\Roles;
use Src\Model\Table\Services;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;

class AdminDataSource extends WorkerDataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectAllAdminsRows() {
        $this->builder->selectCount('*', 'number')
                ->from(Admins::$table)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            return $result['number'];
        }
        return $result;
    }

    public function selectAdminIdByEmail(string $email) {
        $this->builder->select([Admins::$id])
                ->from(Admins::$table)
                ->whereEqual(Admins::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // admins.id -> id
            $key = explode('.', Admins::$id)[1];
            return $result[$key];
        }
        return $result;
    }

    public function insertAdmin(AdminWriteDTO $admin) {
        $currentDatetime = date('Y-m-d H:i:s');
        $this->builder->insertInto(Admins::$table,
                    [Admins::$name, Admins::$surname, Admins::$password,
                     Admins::$email, Admins::$created_date, Admins::$role_id])
                ->values(
                    [':name', ':surname', ':password', ':email', ':created_datetime'],
                    [$admin->getName(), $admin->getSurname(), $admin->getPasswordHash(),
                     $admin->getEmail(), $currentDatetime],
                    true
                )   ->subqueryBegin()
                        ->select([Roles::$id])
                        ->from(Roles::$table)
                        ->whereEqual(Roles::$name, ':admin_role', $admin->getRole())
                    ->subqueryEnd()
                ->queryEnd()
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }
    public function insertAdminSetting(int $adminId) {
        $this->builder->insertInto(AdminsSetting::$table, [AdminsSetting::$admin_id])
                ->values([':admin_id'], [$adminId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function updateAdmin(AdminWriteDTO $admin) {
        $this->builder->update(Admins::$table)
                ->set(Admins::$name, ':name', $admin->getName())
                ->andSet(Admins::$surname, ':surname', $admin->getSurname())
                ->andSet(Admins::$email, ':email', $admin->getEmail())
                ->andSet(Admins::$password, ':password', $admin->getPasswordHash())
                ->andSet(Admins::$status, ':status', $admin->getStatus())
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
    public function selectAdminPasswordByEmail(string $email)
    {
        $this->builder->select([Admins::$password])
                ->from(Admins::$table)
                ->whereEqual(Admins::$email, ':email', $email)
            ->build();

        $this->db->bind(':email', $email);

        $result = $this->db->singleRow();
        if ($result) {
            // admins.password -> password
            $key = explode('.', Admins::$password)[1];
            return $result[$key];
        }
        return false;
    }

    /**
     * @param int $adminId
     * @return false|array|null
     *
     * [
     *      'name' =>
     *      'surname' =>
     *      'email' =>
     * ]
     */
    public function selectAdminInfoById(int $adminId) {
        $this->builder->select([Admins::$id, Admins::$name, Admins::$surname, Admins::$email])
                ->from(Admins::$table)
                ->whereEqual(Admins::$id, ':id', $adminId)
            ->build();

        return $this->db->singleRow();
    }


    /**
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false|null
     *
     * [
     *      0 => [
     *          id =>
     *          name =>
     *          surname =>
     *          email =>
     *          position =>
     *          salary =>
     *          experience =>
     *      ]
     *      ....................
     * ]
     */
    public function selectAllWorkersForAdmin(
        int $limit, int $offset,
        string $orderByField = 'workers.id', string $orderDirection = 'asc'
    ) {
        $workers = Workers::$table;
        $_position_id = Workers::$position_id;
        $experience = Workers::$years_of_experience;

        $positions = Positions::$table;
        $position_id = Positions::$id;
        $position_name = Positions::$name;

        $queryFrom = "
            $workers INNER JOIN $positions ON $_position_id = $position_id
        ";

        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname, Workers::$email,
                         "$position_name as position", Workers::$salary, "$experience as experience"])
                ->from(Workers::$table)
                ->innerJoin(Positions::$table)
                    ->on(Workers::$position_id, Positions::$id)
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

    public function selectAllPositions() {
        $this->builder->select([Positions::$id, Positions::$name])
                ->from(Positions::$table)
            ->build();

        return $this->db->manyRows();
    }

    public function selectAllRoles() {
        $this->builder->select([Roles::$id, Roles::$name])
                ->from(Roles::$table)
            ->build();

        return $this->db->manyRows();
    }

    public function selectServicesAllByDepartmentId(int $departmentId)
    {
        $this->builder->select([Services::$id, Services::$name])
                ->from(Services::$table)
                ->whereEqual(Services::$department_id, ':department_id', $departmentId)
            ->build();

        return $this->db->manyRows();
    }

    public function selectDepartmentsAllForAdmin(
        int $limit, int $offset,
        string $orderByField = 'departments.id', string $orderDirection = 'asc'
    ) {
        $departments = Departments::$table;
        $queryFrom = " $departments ";

        $this->builder->select([Departments::$id, Departments::$name])
                ->from(Departments::$table)
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

    public function insertDepartment(string $name)
    {
        $this->builder->insertInto(Departments::$table, [Departments::$name])
                    ->values([':name'], [$name])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function updateDepartmentName(int $id, string $name)
    {
        $this->builder->update(Departments::$table)
                    ->set(Departments::$name, ':name', $name)
                    ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function deleteDepartmentById(int $id)
    {
        $this->builder->delete()
                    ->from(Departments::$table)
                    ->whereEqual(Departments::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectFutureOrdersByDepartmentId(int $departmentId)
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

    public function selectFutureOrdersByWorkerId(int $workerId)
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

    public function selectPositionsAllWithDepartments(
        int $limit, int $offset,
        string $orderByField = 'positions.id', string $orderDirection = 'asc'
    ){
        $positions = Positions::$table;
        $queryFrom = " $positions ";

        $this->builder->select([Positions::$id, Positions::$name,
                                Positions::$department_id, Departments::$name],
                                [Departments::$name => 'department_name'])
            ->from(Positions::$table)
            ->innerJoin(Departments::$table)
                ->on(Positions::$department_id, Departments::$id)
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

    public function insertPosition(string $name, int $departmentId)
    {
        $this->builder->insertInto(Positions::$table,
                                  [Positions::$name, Positions::$department_id],)
                         ->values([':name', ':department_id'], [$name, $departmentId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function selectPositionIdByNameAndDepartment(string $name, int $departmentId)
    {
        $this->builder->select([Positions::$id])
                    ->from(Positions::$table)
                    ->whereEqual(Positions::$name, ':name', $name)
                    ->andEqual(Positions::$department_id, ':department_id', $departmentId)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            // positions.id -> id
            return $result[explode('.', Positions::$id)[1]];
        }
        return $result;
    }

    public function selectPositionById(int $id)
    {
        $this->builder->select([Positions::$id, Positions::$name, Positions::$department_id])
                    ->from(Positions::$table)
                    ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function updatePositionById(int $id, string $name, int $departmentId)
    {
        $this->builder->update(Positions::$table)
                    ->set(Positions::$name, ':name', $name)
                    ->andSet(Positions::$department_id, ':department_id', $departmentId)
                    ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectPositionWithDepartmentById(int $id)
    {
        $this->builder->select([Positions::$id, Positions::$name, Positions::$department_id,
                                Departments::$name],
                            [Departments::$name => 'department_name'])
                    ->from(Positions::$table)
                    ->innerJoin(Departments::$table)
                        ->on(Positions::$department_id, Departments::$id)
                    ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function selectFutureOrdersByPositionId(int $positionId)
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

    public function deletePositionById(int $id)
    {
        $this->builder->delete()
                    ->from(Positions::$table)
                    ->whereEqual(Positions::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectAllAffiliatesForAdminTable(
        int $limit, int $offset,
        string $orderByField = 'affiliates.id', string $orderDirection = 'asc'
    ){
        $affiliates = Affiliates::$table;
        $queryFrom = " $affiliates ";

        $this->builder->select([Affiliates::$id, Affiliates::$name, Affiliates::$country,
                                Affiliates::$city, Affiliates::$address, Affiliates::$created_date,
                                Workers::$id, Workers::$name, Workers::$surname],
                        [Workers::$id => 'manager_id',
                         Workers::$name => 'manager_name',
                         Workers::$surname => 'manager_surname'])
            ->from(Affiliates::$table)
            ->leftJoin(Workers::$table)
                ->on(Affiliates::$worker_manager_id, Workers::$id)
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

    public function insertAffiliate(
        string $name, string $country, string $city, string $address, ?int $managerId = null
    ) {
        $this->builder->insertInto(Affiliates::$table,
                        [Affiliates::$name, Affiliates::$country,
                         Affiliates::$city, Affiliates::$worker_manager_id,
                         Affiliates::$address])
                    ->values([':name', ':country', ':city', ':manager_id', ':address'],
                            [$name, $country, $city, $managerId, $address])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function selectAffiliateById(int $id)
    {
        $this->builder->select([Affiliates::$id, Affiliates::$name, Affiliates::$country,
                                Affiliates::$city, Affiliates::$address,
                                Affiliates::$worker_manager_id],
                        [Affiliates::$worker_manager_id => 'manager_id'])
                        ->from(Affiliates::$table)
                        ->whereEqual(Affiliates::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function selectAffiliateByIdForTable(int $id)
    {
        $this->builder->select([Affiliates::$id, Affiliates::$name, Affiliates::$country,
                                Affiliates::$city, Affiliates::$address, Affiliates::$created_date,
                                Workers::$id, Workers::$name, Workers::$surname],
            [
                Workers::$id => 'manager_id',
                Workers::$name => 'manager_name',
                Workers::$surname => 'manager_surname'
            ])
            ->from(Affiliates::$table)
            ->leftJoin(Workers::$table)
                ->on(Affiliates::$worker_manager_id, Workers::$id)
            ->whereEqual(Affiliates::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function selectAffiliateByAddressAndNotId(
        int $id, string $country, string $city, string $address
    ) {
        $this->builder->select([Affiliates::$id])
                    ->from(Affiliates::$table)
                    ->whereEqual(Affiliates::$country, ':country', $country)
                    ->andEqual(Affiliates::$city, ':city', $city)
                    ->andEqual(Affiliates::$address, ':address', $address)
                    ->andNotEqual(Affiliates::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function updateAffiliateById(
        int $id, string $name, string $country,
        string $city, string $address, ?int $managerId = null
    ) {
        $this->builder->update(Affiliates::$table)
                    ->set(Affiliates::$name, ':name', $name)
                    ->andSet(Affiliates::$country, ':country', $country)
                    ->andSet(Affiliates::$city, ':city', $city)
                    ->andSet(Affiliates::$address, ':address', $address)
                    ->andSet(Affiliates::$worker_manager_id, ':manager_id', $managerId)
                ->whereEqual(Affiliates::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectFutureOrdersByAffiliateId(int $affiliateId)
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

    public function deleteAffiliateById(int $id)
    {
        $this->builder->delete()
                    ->from(Affiliates::$table)
                    ->whereEqual(Affiliates::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}