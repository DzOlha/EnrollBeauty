<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\Table\Admins;
use Src\Model\Table\AdminsSetting;
use Src\Model\Table\Positions;
use Src\Model\Table\Roles;
use Src\Model\Table\Workers;

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
}