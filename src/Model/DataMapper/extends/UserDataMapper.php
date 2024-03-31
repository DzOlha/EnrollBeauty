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

    public function insertNewUserPhoto(int $userId, int $isMain = 0)
    {
        return $this->dataSource->insertNewUserPhoto($userId, $isMain);
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
        int    $userId, int $limit, int $offset,
        string $orderByField = 'orders_service.id', string $orderDirection = 'asc')
    {
        return $this->dataSource->selectUserComingAppointments(
            $userId, $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function selectWorkerScheduleItemById(int $scheduleId)
    {
        return $this->dataSource->selectWorkerScheduleItemById($scheduleId);
    }

    public function selectUserEmailById(int $userId)
    {
        return $this->dataSource->selectUserEmailById($userId);
    }

    public function selectOrderServiceByScheduleId(int $scheduleId)
    {
        return $this->dataSource->selectOrderServiceByScheduleId($scheduleId);
    }

    public function insertOrderService(
        ?int $scheduleId, ?int $userId, string $email, int $priceId,
        int  $affiliateId, string $startDatetime, string $endDatetime
    )
    {
        return $this->dataSource->insertOrderService(
            $scheduleId, $userId, $email, $priceId,
            $affiliateId, $startDatetime, $endDatetime
        );
    }

    public function updateOrderIdInWorkersServiceSchedule(
        int $scheduleId, int $orderId
    )
    {
        return $this->dataSource->updateOrderIdInWorkersServiceSchedule(
            $scheduleId, $orderId
        );
    }

    public function selectScheduleIdByOrderId(int $orderId)
    {
        return $this->dataSource->selectScheduleIdByOrderId($orderId);
    }

    public function selectScheduleForUserByTimeInterval(
        string $email, string $startDatetime, string $endDatetime
    ) {
        return $this->dataSource->selectScheduleForUserByTimeInterval(
            $email, $startDatetime, $endDatetime
        );
    }
}