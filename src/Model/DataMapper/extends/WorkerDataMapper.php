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
    public function selectWorkerOrderedSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null
    )
    {
        return $this->dataSource->selectWorkerOrderedSchedule(
            $departmentId, $serviceId,
            $workerId, $affiliateId,
            $dateFrom, $dateTo,
            $timeFrom, $timeTo,
            $priceFrom, $priceTo
        );
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
     * @return mixed  * response example
     *  [
     *       0 => [
     *          'schedule_id' =>,
     *          'service_id' =>,
     *          'service_name' =>,
     *          'affiliate_id' =>,
     *          'city' =>,
     *          'address' =>,
     *          'day' =>,
     *          'start_time' =>,
     *          'end_time' =>,
     *          'price' =>,
     *          'currency' =>
     *       ]
     *  ..........................
     *  ]
     */
    public function selectWorkerFreeSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null
    )
    {
        return $this->dataSource->selectWorkerFreeSchedule(
            $departmentId, $serviceId,
            $workerId, $affiliateId,
            $dateFrom, $dateTo,
            $timeFrom, $timeTo,
            $priceFrom, $priceTo
        );
    }

    public function selectWorkerScheduleById(int $scheduleId)
    {
        return $this->dataSource->selectWorkerScheduleById($scheduleId);
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

    public function selectWorkerServicePricingByIds(int $workerId, $serviceId)
    {
        return $this->dataSource->selectWorkerServicePricingByIds($workerId, $serviceId);
    }

    public function insertWorkerServicePricing(
        int $workerId, int $serviceId, $price
    )
    {
        return $this->dataSource->insertWorkerServicePricing($workerId, $serviceId, $price);
    }

    /**
     * @param int $workerId
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return mixed
     * [
     *  0 => [
     *       'id' =>
     *       'name' => service name
     *       'service_id' =>
     *       'price' =>
     *       'currency' =>
     *       'updated_datetime' =>
     *   ]
     * ......
     * 'totalRowsCount':
     *  ]
     * /
     */
    public function selectAllWorkersServicePricing(
        int    $workerId, int $limit, int $offset,
        string $orderByField = 'workers_service_pricing.id', string $orderDirection = 'asc'
    )
    {
        return $this->dataSource->selectAllWorkersServicePricing(
            $workerId, $limit, $offset, $orderByField, $orderDirection
        );
    }

    /**
     * @param int $workerId
     * @param string $day
     * @return array|false
     *
     *  [
     *       0 => [
     *           'start_time' =>
     *           'end_time' =>
     *       ]
     *   ........................
     *  ]
     */
    public function selectFilledTimeIntervalsByWorkerIdAndDay(
        int $workerId, string $day
    ) {
        return $this->dataSource->selectFilledTimeIntervalsByWorkerIdAndDay($workerId, $day);
    }

    public function selectScheduleForWorkerByDayAndTime(
        int $workerId, string $day, string $startTime, string $endTime
    ) {
        return $this->dataSource->selectScheduleForWorkerByDayAndTime(
            $workerId, $day, $startTime, $endTime
        );
    }

    public function selectPriceIdByWorkerIdServiceId(
        int $workerId, int $serviceId
    ) {
        return $this->dataSource->selectPriceIdByWorkerIdServiceId($workerId, $serviceId);
    }

    public function insertWorkerServiceSchedule(
        int $priceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    ) {
        return $this->dataSource->insertWorkerServiceSchedule(
            $priceId, $affiliateId, $day, $startTime, $endTime
        );
    }

    public function selectAllServicesWithDepartments(
        int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    ){
        return $this->dataSource->selectAllServicesWithDepartments(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function selectServiceIdByNameAndDepartmentId(
        string $serviceName, int $departmentId
    ) {
        return $this->dataSource->selectServiceIdByNameAndDepartmentId(
            $serviceName, $departmentId
        );
    }

    public function insertNewService(string $serviceName, int $departmentId)
    {
        return $this->dataSource->insertNewService($serviceName, $departmentId);
    }

    public function updateWorkerServicePricing(int $workerId, int $serviceId, $price)
    {
        return $this->dataSource->updateWorkerServicePricing($workerId, $serviceId, $price);
    }

    public function selectActiveOrdersByPricingId(int $pricingId)
    {
        return $this->dataSource->selectActiveOrdersByPricingId($pricingId);
    }

    public function deleteWorkerServicePricingById(int $pricingId)
    {
        return $this->dataSource->deleteWorkerServicePricingById($pricingId);
    }

    public function selectServiceById(int $id)
    {
        return $this->dataSource->selectServiceById($id);
    }

    public function updateServiceById(
        int $id, string $name, int $departmentId
    ) {
        return $this->dataSource->updateServiceById(
            $id, $name, $departmentId
        );
    }

    public function selectActiveOrdersByServiceId(int $serviceId)
    {
        return $this->dataSource->selectActiveOrdersByServiceId($serviceId);
    }

    public function deleteServiceById(int $serviceId)
    {
        return $this->dataSource->deleteServiceById($serviceId);
    }

    public function selectServiceWithDepartmentById(int $id)
    {
        return $this->dataSource->selectServiceWithDepartmentById($id);
    }

    public function selectWorkerServicePricing(int $workerId, int $serviceId)
    {
        return $this->dataSource->selectWorkerServicePricing($workerId, $serviceId);
    }

    public function updateWorkerServiceSchedule(
        int $id, int $priceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    ) {
        return $this->dataSource->updateWorkerServiceSchedule(
            $id, $priceId, $affiliateId, $day, $startTime, $endTime
        );
    }

    public function selectEditFilledTimeIntervalsByWorkerIdAndDay(
        int $workerId, string $day, int $scheduleId
    ){
        return $this->dataSource->selectEditFilledTimeIntervalsByWorkerIdAndDay(
            $workerId, $day, $scheduleId
        );
    }

    public function selectEditScheduleForWorkerByDayAndTime(
        int $workerId, string $day, string $startTime,
        string $endTime, int $scheduleId
    ) {
        return $this->dataSource->selectEditScheduleForWorkerByDayAndTime(
            $workerId, $day, $startTime, $endTime, $scheduleId
        );
    }

    public function deleteWorkerScheduleItemById(int $id)
    {
        return $this->dataSource->deleteWorkerScheduleItemById($id);
    }
}