<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Read\UserSocialReadDto;
use Src\Model\DTO\Write\UserWriteDto;

class UserDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function insertNewUser(UserWriteDto $user): int | false
    {
        return $this->repositoryPool->user()->insert($user);
    }

    public function insertNewUserSetting(int $userId): int | false
    {
        return $this->repositoryPool->user()->insertSettings($userId);
    }

    public function insertNewUserSocial(int $userId): int | false
    {
       return $this->repositoryPool->user()->insertSocials($userId);
    }

    public function insertNewUserPhoto(int $userId, int $isMain = 0): int | false
    {
        return $this->repositoryPool->user()->insertPhoto($userId, $isMain);
    }

    /**
     * @param int $userId
     * @return UserSocialReadDto|false
     *
     * return = [
     *      'id' =>
     *      'user_id' =>
     *      'Instagram' =>
     *      'TikTok' =>
     *      'Facebook' =>
     *      'YouTube' =>
     * ]
     */
    public function selectUserSocialById(int $userId): array | false
    {
        return $this->repositoryPool->user()->insertSocials($userId);
    }

    public function selectUserComingAppointments(
        int    $userId, int $limit, int $offset,
        string $orderByField = 'orders_service.id', string $orderDirection = 'asc'
    ): array | false
    {
        return $this->repositoryPool->orderService()->selectAllUpcomingLimitedByUserId(
            $userId, $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function selectWorkerScheduleItemById(int $scheduleId): string | false
    {
        return $this->repositoryPool->schedule()->select($scheduleId);
    }

    public function selectUserEmailById(int $userId): string | false
    {
        return $this->repositoryPool->user()->selectEmail($userId);
    }

    public function selectOrderServiceByScheduleId(int $scheduleId): array | false
    {
        return $this->repositoryPool->orderService()->selectByScheduleId($scheduleId);
    }

    public function insertOrderService(
        ?int $scheduleId, ?int $userId, string $email, int $priceId,
        int $affiliateId, string $startDatetime, string $endDatetime
    ): int | false
    {
        return $this->repositoryPool->orderService()->insert(
            $scheduleId, $userId, $email, $priceId,
            $affiliateId, $startDatetime, $endDatetime
        );
    }

    public function updateOrderIdInWorkersServiceSchedule(
        int $scheduleId, int $orderId
    ): bool
    {
        return $this->repositoryPool->schedule()->updateOrderId($scheduleId, $orderId);
    }

    public function selectScheduleForUserByTimeInterval(
        string $email, string $startDatetime, string $endDatetime
    ): array | false
    {
        return $this->repositoryPool->schedule()->selectAllByUserEmailAndTime(
            $email, $startDatetime, $endDatetime
        );
    }

    public function updateUserSocialNetworksById(int $id, array $socials)
    {
        return $this->repositoryPool->user()->updateSocials($id, $socials);
    }

    public function selectUserPersonalInfoById(int $id)
    {
        return $this->repositoryPool->user()->selectWithPhoto($id);
    }

    public function updateUserPersonalInfoById(
        int $id, string $name, string $surname, string $email
    ): bool {
       return $this->repositoryPool->user()->update($id, $name, $surname, $email);
    }

    public function selectUserMainPhotoByUserId(int $userId)
    {
        return $this->repositoryPool->user()->selectPhoto($userId);
    }

    public function updateUserMainPhotoByUserId(int $userId, string $filename)
    {
        return $this->repositoryPool->user()->updatePhoto($userId, $filename);
    }

    /**
     * @param int $id
     * @return array|false =
     * {
     *      id:
     *      email:
     *      start_datetime:
     *      price:
     *      currency:
     *      city:
     *      address:
     *      user_name:
     *      user_surname:
     *      worker_id:
     *      worker_name:
     *      worker_surname:
     *      service_name:
     * }
     */
    public function selectOrderDetailsById(int $id)
    {
        return $this->repositoryPool->orderService()->select($id);
    }
}