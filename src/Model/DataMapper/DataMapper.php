<?php

namespace Src\Model\DataMapper;

use Src\Model\DataSource\DataSource;

abstract class DataMapper
{
    protected DataSource $dataSource;

    public function __construct(DataSource $ds)
    {
        $this->dataSource = $ds;
    }

    public function beginTransaction(): void
    {
        $this->dataSource->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->dataSource->commitTransaction();
    }

    public function rollBackTransaction(): void
    {
        $this->dataSource->rollBackTransaction();
    }

    public function insertNewUser(string $name, string $surname, string $email, string $passwordHash) {
        return $this->dataSource->insertNewUser($name, $surname, $email, $passwordHash);
    }
    public function insertNewUserSetting(int $userId) {
        return $this->dataSource->insertNewUserSetting($userId);
    }
    public function insertNewUserSocial(int $userId) {
        return $this->dataSource->insertNewUserSocial($userId);
    }
    public function insertNewUserPhoto(int $userId) {
        return $this->dataSource->insertNewUserPhoto($userId);
    }

    public function selectUserPasswordByEmail(string $email) {
        return $this->dataSource->selectUserPasswordByEmail($email);
    }

    public function selectUserIdByEmail(string $email) {
        return $this->dataSource->selectUserIdByEmail($email);
    }
}