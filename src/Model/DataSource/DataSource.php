<?php

namespace Src\Model\DataSource;

use Src\DB\IDatabase;
use Src\Model\Table\Roles;
use Src\Model\Table\Users;
use Src\Model\Table\UsersPhoto;
use Src\Model\Table\UsersSetting;
use Src\Model\Table\UsersSocial;

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
    public function insertNewUser(string $name, string $surname, string $email, string $passwordHash) {
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

    public function insertNewUserSetting(int $userId) {
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
    public function insertNewUserSocial(int $userId) {
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
    public function insertNewUserPhoto(int $userId) {
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

    public function selectUserPasswordByEmail(string $email) {
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
    public function selectUserIdByEmail(string $email) {
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
}