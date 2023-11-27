<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
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
        $admins = Admins::$table;

        $this->db->query("
            SELECT COUNT(*) AS number FROM $admins
        ");

        $result = $this->db->singleRow();
        if($result) {
            return $result['number'];
        }
        return $result;
    }

    public function selectAdminIdByEmail(string $email) {
        $admins = Admins::$table;
        $_email = Admins::$email;
        $_id = Admins::$id;

        $this->db->query(
            "SELECT $_id FROM $admins WHERE $_email = :email"
        );

        $this->db->bind(':email', $email);

        $result = $this->db->singleRow();
        if ($result) {
            // admins.id -> id
            $key = explode('.', $_id)[1];
            return $result[$key];
        }
        return $result;
    }

    public function insertAdmin(AdminWriteDTO $admin) {
        $admins = Admins::$table;
        $_name = Admins::$name;
        $_surname = Admins::$surname;
        $_email = Admins::$email;
        $_password = Admins::$password;
        $_created_date = Admins::$created_date;
        $_role_id = Admins::$role_id;

        $roles = Roles::$table;
        $role_id = Roles::$id;
        $role_name = Roles::$name;

        $this->db->query(
            "INSERT INTO $admins ($_name, $_surname, $_password, $_email, $_created_date, $_role_id)
                VALUES (:name, :surname, :password, :email, NOW(), 
                        (SELECT $role_id FROM $roles WHERE $role_name = :admin_role))"
        );
        $this->db->bind(':name', $admin->getName());
        $this->db->bind(':surname', $admin->getSurname());
        $this->db->bind(':password', $admin->getPasswordHash());
        $this->db->bind(':email', $admin->getEmail());

        $this->db->bind(':admin_role', $admin->getRole());

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }
    public function insertAdminSetting(int $adminId) {
        $adminsSetting = AdminsSetting::$table;
        $admin_id = AdminsSetting::$admin_id;
        $this->db->query(
            "INSERT INTO $adminsSetting ($admin_id) VALUES (:admin_id)"
        );
        $this->db->bind(':admin_id', $adminId);

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function updateAdmin(AdminWriteDTO $admin) {
        $admins = Admins::$table;
        $_name = Admins::$name;
        $_surname = Admins::$surname;
        $_email = Admins::$email;
        $_password = Admins::$password;
        $_status = Admins::$status;

        $this->db->query("
            UPDATE $admins
            SET $_name = :name, $_surname = :surname, $_email = :email,
                $_password = :password, $_status = :status
        ");

        $this->db->bind(':name', $admin->getName());
        $this->db->bind(':surname', $admin->getSurname());
        $this->db->bind(':email', $admin->getEmail());
        $this->db->bind(':password', $admin->getPasswordHash());
        $this->db->bind(':status', $admin->getStatus());

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
    public function selectAdminPasswordByEmail(string $email)
    {
        $users = Admins::$table;
        $_email = Admins::$email;
        $_password = Admins::$password;

        $this->db->query(
            "SELECT $_password FROM $users WHERE $_email = :email"
        );

        $this->db->bind(':email', $email);

        $result = $this->db->singleRow();
        if ($result) {
            // admins.password -> password
            $key = explode('.', $_password)[1];
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
        $admins = Admins::$table;
        $name = Admins::$name;
        $surname = Admins::$surname;
        $email = Admins::$email;

        $_admin_id = Admins::$id;

        $this->db->query("
            SELECT $_admin_id, $name, $surname, $email
            FROM $admins
            WHERE $_admin_id = :id
        ");
        $this->db->bind(':id', $adminId);

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
        $id = Workers::$id;
        $name = Workers::$name;
        $surname = Workers::$surname;
        $email = Workers::$email;
        $_position_id = Workers::$position_id;
        $salary = Workers::$salary;
        $experience = Workers::$years_of_experience;

        $positions = Positions::$table;
        $position_id = Positions::$id;
        $position_name = Positions::$name;

        $queryFrom = "
            $workers INNER JOIN $positions ON $_position_id = $position_id
        ";
        $this->db->query("
            SELECT $id, $name, $surname, $email, 
                   $position_name as position, $salary, $experience as experience
            FROM $workers INNER JOIN $positions ON $_position_id = $position_id
            
            ORDER BY $orderByField $orderDirection
            LIMIT $limit
            OFFSET $offset
        ");

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result);
    }

    public function selectAllPositions() {
        $positions = Positions::$table;
        $id = Positions::$id;
        $name = Positions::$name;

        $this->db->query("
            SELECT $id, $name FROM $positions
        ");

        return $this->db->manyRows();
    }

    public function selectAllRoles() {
        $roles = Roles::$table;
        $id = Roles::$id;
        $name = Roles::$name;

        $this->db->query("
            SELECT $id, $name FROM $roles
        ");

        return $this->db->manyRows();
    }
}