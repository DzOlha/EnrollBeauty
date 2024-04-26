<?php

namespace Src\Model\DataSource;

use Src\DB\IDatabase;
use Src\Helper\Builder\IBuilder;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\Repository\Pool\impl\RepositoryPool;
use Src\Model\Repository\Pool\IRepositoryPool;

abstract class DataSource
{
    protected ?IDatabase $db = null;
    protected IRepositoryPool $repositoryPool;

    /**
     * @param IDatabase $db
     * @param IBuilder|null $builder
     * @param IRepositoryPool|null $repositoryPool
     */
    public function __construct(
        IDatabase $db, IBuilder $builder = null,
        IRepositoryPool $repositoryPool = null
    ){
        if (!$this->db) {
            $this->db = $db;
        }
        $this->repositoryPool = $repositoryPool ??
            new RepositoryPool($this->db, $builder ?? new SqlBuilder($this->db));
    }

    public function setRepositoryPool(IRepositoryPool $pool): void
    {
        $this->repositoryPool = $pool;
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

    public function selectUserPasswordByEmail(string $email): string | false
    {
        return $this->repositoryPool->user()->selectPasswordByEmail($email);
    }

    public function selectUserIdByEmail(string $email): int | false
    {
        return $this->repositoryPool->user()->selectIdByEmail($email);
    }

    /**
     * @param int $userId
     * @return UserReadDto|false
     *
     * return = [
     *      'id' =>
     *      'name' =>
     *      'surname' =>
     *      'email' =>
     *      'filename' =>
     * ]
     *
     */
    public function selectUserInfoById(int $userId): array | false
    {
        return $this->repositoryPool->user()->selectWithPhoto($userId);
    }

    /**
     * @param int $serviceId
     * @return array|false
     *
     * return = [
     *      0 => [
     *          'id' => ,
     *          'name' => ,
     *          'surname' => ,
     *      ]
     * .......
     * ]
     */
    public function selectWorkersForService(int $serviceId): array | false
    {
        return $this->repositoryPool->worker()->selectAllByServiceId($serviceId);
    }

    /**
     * @param int $workerId
     * @return array|false
     *
     *  * return = [
     *      0 => [
     *          'id' => ,
     *          'name' => ,
     *      ]
     * .......
     * ]
     */
    public function selectServicesForWorker(int $workerId): array | false
    {
        return $this->repositoryPool->service()->selectAllByWorkerId($workerId);
    }

    /**
     * @return array|false
     *
     * return = [
     *     0 => [
     *      'id' =>,
     *      'name' =>,
     *    ]
     * ........
     * ]
     */
    public function selectAllServices(): array | false
    {
        return $this->repositoryPool->service()->selectAll();
    }

    /**
     * @return array|false
     *
     * return = [
     *     0 => [
     *      'id' =>,
     *      'name' =>,
     *      'surname' =>
     *    ]
     * ........
     * ]
     */
    public function selectAllWorkers(): array | false
    {
        return $this->repositoryPool->worker()->selectAll();
    }

    /**
     * @return array|false
     *
     * return = [
     *     0 => [
     *      'id' =>,
     *      'name' =>,
     *      'city' =>,
     *      'address' =>
     *    ]
     * ........
     * ]
     */
    public function selectAllAffiliates(): array | false
    {
        return $this->repositoryPool->affiliate()->selectAll();
    }

    /**
     * @return array|false
     *
     * [
     *      0 => [
     *          'id' =>,
     *          'name' =>,
     *      ]
     *      ......
     * ]
     */
    public function selectAllDepartments()
    {
        return $this->repositoryPool->department()->selectAll();
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
     * @return array|false
     *
     * response example
     * [
     *      0 => [
     *         'schedule_id' =>,
     *         'service_id' =>,
     *         'department_id' =>,
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
     * ...............................
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
        return $this->repositoryPool->schedule()->selectAll(
            $departmentId, $serviceId, $workerId, $affiliateId,
            $dateFrom, $dateTo, $timeFrom, $timeTo, $priceFrom, $priceTo
        );
    }

    /**
     * @param int $serviceId
     * @return array|false
     *
     * response example
     * [
     *      'id' =>,
     *      'nam'e' =>
     * ]
     */
    public function selectDepartmentByServiceId(int $serviceId)
    {
        return $this->repositoryPool->department()->selectByServiceId($serviceId);
    }

    public function updateServiceOrderCanceledDatetimeById(int $orderId)
    {
        return $this->repositoryPool->orderService()->updateCancel($orderId);
    }

    public function updateOrderIdByScheduleId(int $scheduleId)
    {
        return $this->repositoryPool->schedule()->updateOrderIdToNull($scheduleId);
    }

    public function updateCompletedDatetimeByOrderId(int $orderId)
    {
        return $this->repositoryPool->orderService()->updateComplete($orderId);
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
        return $this->repositoryPool->orderService()->selectAllLimited(
            $limit, $offset, $orderField, $orderDirection, $departmentId,
            $serviceId, $workerId, $affiliateId, $dateFrom, $dateTo,
            $priceFrom, $priceTo, $userId, $status
        );
    }

    public function updateCompletedDatetimeByOrderIds(array $ids)
    {
        return $this->repositoryPool->orderService()->updateCompleteMany($ids);
    }

    public function deleteOrdersByIds(array $ids)
    {
        return $this->repositoryPool->orderService()->deleteMany($ids);
    }

    public function updateCanceledDatetimeByOrderIds(array $ids)
    {
        return $this->repositoryPool->orderService()->updateCancelMany($ids);
    }

    public function selectScheduleIdByOrderId(int $orderId)
    {
        return $this->repositoryPool->schedule()->selectIdByOrderId($orderId);
    }
}