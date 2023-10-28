<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\UserDataSource;

class UserDataMapper extends DataMapper
{
    public function __construct(UserDataSource $ds)
    {
        parent::__construct($ds);
    }

    public function insertNewUser(string $name, string $surname, string $email, string $passwordHash)
    {
        return $this->dataSource->insertNewUser($name, $surname, $email, $passwordHash);
    }

    public function insertNewUserSetting(int $userId)
    {
        return $this->dataSource->insertNewUserSetting($userId);
    }

    public function insertNewUserSocial(int $userId)
    {
        return $this->dataSource->insertNewUserSocial($userId);
    }

    public function insertNewUserPhoto(int $userId)
    {
        return $this->dataSource->insertNewUserPhoto($userId);
    }

    public function selectUserInfoById(int $userId)
    {
        return $this->dataSource->selectUserInfoById($userId);
    }

    public function selectUserSocialById(int $userId)
    {
        return $this->dataSource->selectUserSocialById($userId);
    }

    public function selectUserComingAppointments(int $userId)
    {
        return $this->dataSource->selectUserComingAppointments($userId);
    }
}