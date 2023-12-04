<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Model\DTO\Write\WorkerWriteDTO;

class WorkerDataMapper extends DataMapper
{
    public function __construct(WorkerDataSource $ds)
    {
        parent::__construct($ds);
    }

    public function selectWorkerByEmail(string $email)
    {
        return $this->dataSource->selectWorkerByEmail($email);
    }

    public function insertWorker(WorkerWriteDTO $worker)
    {
        return $this->dataSource->insertWorker($worker);
    }

    public function insertWorkerSettings(int $workerId, string $recoveryCode)
    {
        return $this->dataSource->insertWorkerSettings($workerId, $recoveryCode);
    }

    public function insertWorkerSocial(int $workerId)
    {
        return $this->dataSource->insertWorkerSocial($workerId);
    }

    public function updateWorkerSettingDateOfSendingRecoveryCode(
        int $id, string $recoveryCode
    )
    {
        return $this->dataSource->updateWorkerSettingDateOfSendingRecoveryCode(
            $id, $recoveryCode
        );
    }

    public function selectWorkerDateSendingByRecoveryCode(
        string $recoveryCode
    )
    {
        return $this->dataSource->selectWorkerDateSendingByRecoveryCode($recoveryCode);
    }

    public function updateWorkerPasswordByRecoveryCode(
        string $recoveryCode, string $passwordHash
    )
    {
        return $this->dataSource->updateWorkerPasswordByRecoveryCode(
            $recoveryCode, $passwordHash
        );
    }

    public function selectWorkerPasswordByEmail(string $email)
    {
        return $this->dataSource->selectWorkerPasswordByEmail($email);
    }

    public function selectWorkerIdByEmail(string $email)
    {
        return $this->dataSource->selectWorkerIdByEmail($email);
    }

    public function updateRecoveryCodeByRecoveryCode(string $recoveryCode)
    {
        return $this->dataSource->updateRecoveryCodeByRecoveryCode($recoveryCode);
    }

    public function selectWorkerInfoById(int $workerId)
    {
        return $this->dataSource->selectWorkerInfoById($workerId);
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
     *         'order_id' =>,
     *         'service_id' =>,
     *         'service_name' =>,
     *         'user_id' =>,
     *         'user_email' =>,
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
    public function selectWorkerSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null
    )
    {
        return $this->dataSource->selectWorkerSchedule(
            $departmentId, $serviceId,
            $workerId, $affiliateId,
            $dateFrom, $dateTo,
            $timeFrom, $timeTo,
            $priceFrom, $priceTo,
        );
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
    public function selectDepartmentsForWorker(int $workerId)
    {
        return $this->dataSource->selectDepartmentsForWorker($workerId);
    }

    /**
     * @param int $orderId
     * @return array|false
     *
     * [
     *  'user_id' =>
     *  'email' =>
     * ]
     */
    public function selectUserByOrderId(int $orderId)
    {
        return $this->dataSource->selectUserByOrderId($orderId);
    }

    /**
     * @param int $orderId
     * @return array|false
     *
     * [
     *      'name' =>
     *      'start_datetime' =>
     * ]
     */
    public function selectOrderDetails(int $orderId)
    {
        return $this->dataSource->selectOrderDetails($orderId);
    }
}