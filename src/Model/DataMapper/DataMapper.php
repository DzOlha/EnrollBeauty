<?php

namespace Src\Model\DataMapper;

use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Read\UserReadDto;

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

    public function selectUserPasswordByEmail(string $email): string | false
    {
        return $this->dataSource->selectUserPasswordByEmail($email);
    }

    /**
     * @param int $userId
     * @return array|false
     */
    public function selectUserInfoById(int $userId) : array | false
    {
        return $this->dataSource->selectUserInfoById($userId);
    }

    public function selectUserIdByEmail(string $email): int | false
    {
        return $this->dataSource->selectUserIdByEmail($email);
    }

    public function selectWorkersForService(int $serviceId): array | false
    {
        return $this->dataSource->selectWorkersForService($serviceId);
    }

    public function selectServicesForWorker(int $workerId): array | false
    {
        return $this->dataSource->selectServicesForWorker($workerId);
    }

    public function selectAllServices(): array | false
    {
        return $this->dataSource->selectAllServices();
    }

    public function selectAllWorkers(): array | false
    {
        return $this->dataSource->selectAllWorkers();
    }

    public function selectAllAffiliates(): array | false
    {
        return $this->dataSource->selectAllAffiliates();
    }

    public function selectAllDepartments(): array | false
    {
        return $this->dataSource->selectAllDepartments();
    }


    /**
     * @param $departmentId
     * @param $serviceId
     * @param $workerId
     * @param $affiliateId
     * @param $dateFrom
     * @param $dateTo
     * @param $timeFrom
     * @param $timeTo
     * @param $priceFrom
     * @param $priceTo
     * @return mixed
     *
     *  * response example
     * [
     *      0 => [
     *         'schedule_id' =>,
     *         'service_id' =>,
     *         'service_name' =>,
     *         'worker_id' =>,
     *         'worker_name' =>,
     *         'worker_surname' =>,
     *         'affiliate_id' =>,
     *         'city' =>,
     *         'address' =>,
     *         'day' =>,
     *         'start_time' =>,
     *         'end_time' =>,
     *         'price' =>,
     *         'currency' =>
     *      ]
     * ..........................
     * ]
     */
    public function selectSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null
    )
    {
        return $this->dataSource->selectSchedule(
            $departmentId, $serviceId,
            $workerId, $affiliateId,
            $dateFrom, $dateTo,
            $timeFrom, $timeTo,
            $priceFrom, $priceTo,
        );
    }

    public function selectDepartmentByServiceId(int $serviceId): array | false
    {
        return $this->dataSource->selectDepartmentByServiceId($serviceId);
    }

    public function updateServiceOrderCanceledDatetimeById(int $orderId): bool
    {
        return $this->dataSource->updateServiceOrderCanceledDatetimeById($orderId);
    }

    public function updateOrderIdByScheduleId(int $scheduleId): bool
    {
        return $this->dataSource->updateOrderIdByScheduleId($scheduleId);
    }

    public function updateCompletedDatetimeByOrderId(int $orderId): bool
    {
        return $this->dataSource->updateCompletedDatetimeByOrderId($orderId);
    }

    public function selectOrders(
        $limit, $offset,
        $orderField = 'orders_service.id', $orderDirection = 'asc',
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $priceFrom = null, $priceTo = null,
        $userId = null, $status = null
    ) {
        return $this->dataSource->selectOrders(
            $limit, $offset, $orderField, $orderDirection,
            $departmentId, $serviceId, $workerId, $affiliateId,
            $dateFrom, $dateTo, $priceFrom, $priceTo,
            $userId, $status
        );
    }

    public function updateCompletedDatetimeByOrderIds(array $ids)
    {
        return $this->dataSource->updateCompletedDatetimeByOrderIds($ids);
    }
    public function deleteOrdersByIds(array $ids)
    {
        return $this->dataSource->deleteOrdersByIds($ids);
    }
    public function updateCanceledDatetimeByOrderIds(array $ids)
    {
        return $this->dataSource->updateCanceledDatetimeByOrderIds($ids);
    }

    public function selectScheduleIdByOrderId(int $orderId)
    {
        return $this->dataSource->selectScheduleIdByOrderId($orderId);
    }
}