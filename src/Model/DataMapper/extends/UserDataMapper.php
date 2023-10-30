<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\DTO\Write\UserWriteDto;

class UserDataMapper extends DataMapper
{
    public function __construct(UserDataSource $ds)
    {
        parent::__construct($ds);
    }

    public function insertNewUser(UserWriteDto $user)
    {
        return $this->dataSource->insertNewUser($user);
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

    /**
     * @param int $userId
     * @return UserReadDto|false
     */
    public function selectUserInfoById(int $userId)
    {
        return $this->dataSource->selectUserInfoById($userId);
    }

    /**
     * @param int $userId
     * @return
     */
    public function selectUserSocialById(int $userId)
    {
        return $this->dataSource->selectUserSocialById($userId);
    }

    public function selectUserComingAppointments(
        int $userId, int $limit, int $offset,
        string $orderByField = 'orders_service.id', string $orderDirection = 'asc')
    {
        return $this->dataSource->selectUserComingAppointments(
            $userId, $limit, $offset, $orderByField, $orderDirection
        );
    }
}