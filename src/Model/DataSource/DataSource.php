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