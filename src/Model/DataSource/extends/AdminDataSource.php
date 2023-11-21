<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\Table\Admins;
use Src\Model\Table\AdminsSetting;
use Src\Model\Table\Roles;

class AdminDataSource extends DataSource
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
}