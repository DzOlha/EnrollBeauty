<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\WorkerWriteDTO;


class WorkerDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectWorkerByEmail(string $email)
    {
        return $this->repositoryPool->worker()->selectIdByEmail($email);
    }

    public function insertWorker(WorkerWriteDTO $worker)
    {
        return $this->repositoryPool->worker()->insert($worker);
    }

    public function insertWorkerSettings(int $workerId, string $recoveryCode)
    {
       return $this->repositoryPool->worker()->insertSettings($workerId, $recoveryCode);
    }

    public function insertWorkerSocial(int $workerId)
    {
        return $this->repositoryPool->worker()->insertSocials($workerId);
    }

    public function insertWorkerPhoto(int $workerId, int $isMain = 0)
    {
        return $this->repositoryPool->worker()->insertPhoto($workerId, $isMain);
    }

    public function updateWorkerSettingDateOfSendingRecoveryCode(
        int $id, string $recoveryCode
    )
    {
        return $this->repositoryPool->worker()->updateRecoveryCodeSendingDate(
            $id, $recoveryCode
        );
    }

    public function selectWorkerDateSendingByRecoveryCode(
        string $recoveryCode
    ): string | false
    {
        return $this->repositoryPool->worker()->selectRecoveryCodeSendingDate($recoveryCode);
    }

    public function updateWorkerPasswordByRecoveryCode(
        string $recoveryCode, string $passwordHash
    )
    {
        return $this->repositoryPool->worker()->updatePassword($recoveryCode, $passwordHash);
    }

    public function selectWorkerPasswordByEmail(string $email)
    {
        return $this->repositoryPool->worker()->selectPasswordByEmail($email);
    }

    public function selectWorkerIdByEmail(string $email)
    {
       return $this->repositoryPool->worker()->selectIdByEmail($email);
    }

    public function updateRecoveryCodeByRecoveryCode(string $recoveryCode)
    {
        return $this->repositoryPool->worker()->updateRecoveryCodeToNull($recoveryCode);
    }

    public function selectWorkerInfoById(int $workerId)
    {
        return $this->repositoryPool->worker()->selectWithPhoto($workerId);
    }

//    protected function _timeFilter($timeFrom, $timeTo)
//    {
//        $result = [
//            'where' => '',
//            'toBind' => []
//        ];
//        $schedule_start_time = WorkersServiceSchedule::$start_time;
//        $schedule_end_time = WorkersServiceSchedule::$end_time;
//
//        $setFrom = ($timeFrom !== null && $timeFrom !== '');
//        $setTo = ($timeTo !== null && $timeTo !== '');
//
//        if($setFrom) {
//            $result['where'] = " AND $schedule_start_time >= :time_from ";
//            $result['toBind'] += [
//                ':time_from' => $timeFrom
//            ];
//        }
//        if($setTo) {
//            $result['where'] .= " AND $schedule_end_time <= :time_to ";
//            $result['toBind'] += [
//                ':time_to' => $timeTo
//            ];
//        }
//
//        return $result;
//    }

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
     *                  'order_id' =>,
     *         'service_id' =>,
     *         'department_id' =>,
     *         'service_name' =>,
     *                  'user_id' =>,
     *                  'user_email' =>,
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
    public function selectWorkerOrderedSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null,
    )
    {
        return $this->repositoryPool->schedule()->selectAllOrdered(
            $departmentId, $serviceId, $workerId, $affiliateId, $dateFrom,
            $dateTo, $timeFrom, $timeTo, $priceFrom, $priceTo
        );
    }

    /**
     * @param int $scheduleId
     * @return array|false
     *
     * return = [
     *          'schedule_id' =>,
     *          'order_id' => null,
     *          'service_id' =>,
     *          'department_id' =>,
     *          'service_name' =>,
     *          'user_id' => null,
     *          'user_email' => null,
     *          'affiliate_id' =>,
     *          'city' =>,
     *          'address' =>,
     *          'day' =>,
     *          'start_time' =>,
     *          'end_time' =>,
     *          'price' =>,
     *          'currency' =>
     *       ]
     */
    public function selectWorkerScheduleById(int $scheduleId)
    {
        return $this->repositoryPool->schedule()->selectWorkerCard($scheduleId);
    }


    public function selectWorkerFreeSchedule(
        $departmentId = null, $serviceId = null,
        $workerId = null, $affiliateId = null,
        $dateFrom = null, $dateTo = null,
        $timeFrom = null, $timeTo = null,
        $priceFrom = null, $priceTo = null,
    )
    {
        return $this->repositoryPool->schedule()->selectAllFree(
            $departmentId, $serviceId, $workerId, $affiliateId, $dateFrom,
            $dateTo, $timeFrom, $timeTo, $priceFrom, $priceTo
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
        return $this->repositoryPool->department()->selectAllByWorkerId($workerId);
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
        return $this->repositoryPool->orderService()->selectUserIdAndEmail($orderId);
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
        return $this->repositoryPool->orderService()->selectStartDatetimeAndServiceName($orderId);
    }

    public function selectWorkerServicePricingByIds(int $workerId, $serviceId)
    {
        return $this->repositoryPool->servicePricing()->selectIdByWorkerAndService(
            $workerId, $serviceId
        );
    }

    public function insertWorkerServicePricing(
        int $workerId, int $serviceId, $price
    )
    {
        return $this->repositoryPool->servicePricing()->insert(
            $workerId, $serviceId, $price
        );
    }

    /**
     * @param int $workerId
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false
     *
     * [
     * 0 => [
     *      'id' =>
     *      'name' => service name
     *      'service_id' =>
     *      'price' =>
     *      'currency' =>
     *      'updated_datetime' =>
     *  ]
     * ......
     *  'totalRowsCount':
     * ]
     */
    public function selectAllWorkersServicePricing(
        int    $workerId, int $limit, int $offset,
        string $orderByField = 'workers_service_pricing.id', string $orderDirection = 'asc'
    )
    {
        return $this->repositoryPool->servicePricing()->selectAllLimitedByWorkerId(
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
    ){
       return $this->repositoryPool->schedule()->selectBusyIntervalsByWorkerId(
           $workerId, $day
       );
    }

    public function selectScheduleForWorkerByDayAndTime(
        int $workerId, string $day, string $startTime, string $endTime
    )
    {
        return $this->repositoryPool->schedule()->selectAllByWorkerDayTime(
            $workerId, $day, $startTime, $endTime
        );
    }

    public function selectPriceIdByWorkerIdServiceId(
        int $workerId, int $serviceId
    ) {
        return $this->repositoryPool->servicePricing()->selectIdByWorkerService(
            $workerId, $serviceId
        );
    }

    public function insertWorkerServiceSchedule(
        int    $priceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    )
    {
       return $this->repositoryPool->schedule()->insert(
           $priceId, $affiliateId, $day, $startTime, $endTime
       );
    }

    public function selectAllServicesWithDepartments(
        int $workerId, int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    )
    {
        return $this->repositoryPool->service()->selectAllLimitedByWorkerId(
            $workerId, $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function selectServiceIdByNameAndDepartmentId(
        string $serviceName, int $departmentId
    ) {
        return $this->repositoryPool->service()->selectIdByNameAndDepartment(
            $serviceName, $departmentId
        );
    }

    public function insertNewService(string $serviceName, int $departmentId)
    {
        return $this->repositoryPool->service()->insert($serviceName, $departmentId);
    }

    public function updateWorkerServicePricing(int $workerId, int $serviceId, $price)
    {
        return $this->repositoryPool->servicePricing()->update(
            $workerId, $serviceId, $price
        );
    }

    public function selectActiveOrdersByPricingId(int $pricingId)
    {
        return $this->repositoryPool->orderService()->selectAllUpcomingByPricingId($pricingId);
    }

    public function deleteWorkerServicePricingById(int $pricingId)
    {
        return $this->repositoryPool->servicePricing()->delete($pricingId);
    }

    public function selectServiceById(int $id)
    {
        return $this->repositoryPool->service()->select($id);
    }

    public function updateServiceById(
        int $id, string $name, int $departmentId
    ) {
        return $this->repositoryPool->service()->update($id, $name, $departmentId);
    }

    public function selectActiveOrdersByServiceId(int $serviceId)
    {
        return $this->repositoryPool->orderService()->selectAllUpcomingByServiceId($serviceId);
    }

    public function deleteServiceById(int $serviceId)
    {
        return $this->repositoryPool->service()->delete($serviceId);
    }

    public function selectServiceWithDepartmentById(int $id)
    {
       return $this->repositoryPool->service()->selectRow($id);
    }

    public function selectWorkerServicePricing(int $workerId, int $serviceId)
    {
       return $this->repositoryPool->servicePricing()->selectByWorkerAndService(
           $workerId, $serviceId
       );
    }

    public function updateWorkerServiceSchedule(
        int $id, int $priceId, int $affiliateId,
        string $day, string $startTime, string $endTime
    ) {
        return $this->repositoryPool->schedule()->update(
            $id, $priceId, $affiliateId, $day, $startTime, $endTime
        );
    }

    /**
     * @param int $workerId
     * @param string $day
     * @param int $scheduleId
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
    public function selectEditFilledTimeIntervalsByWorkerIdAndDay(
        int $workerId, string $day, int $scheduleId
    ){
        return $this->repositoryPool->schedule()->selectBusyIntervalsByWorkerDayNotId(
            $workerId, $day, $scheduleId
        );
    }

    public function selectEditScheduleForWorkerByDayAndTime(
        int $workerId, string $day, string $startTime,
        string $endTime, int $scheduleId
    ) {
        return $this->repositoryPool->schedule()->selectAllByWorkerAndTimeNotId(
            $workerId, $day, $startTime, $endTime, $scheduleId
        );
    }

    public function deleteWorkerScheduleItemById(int $id)
    {
        return $this->repositoryPool->schedule()->delete($id);
    }

    public function selectWorkerByIdForEdit(int $id)
    {
        return $this->repositoryPool->worker()->select($id);
    }

    public function selectWorkerByEmailAndNotId(int $id, string $email)
    {
        return $this->repositoryPool->worker()->selectAllByEmailNotId($id, $email);
    }

    public function selectWorkerRowById(int $id)
    {
        return $this->repositoryPool->worker()->selectRow($id);
    }

    public function updateWorkerById(
        int $id, string $name, string $surname, string $email,
        int $positionId, int $roleId, ?string $gender, int $age,
        float $experience, ?float $salary
    ) {
        return $this->repositoryPool->worker()->updateFull(
            $id, $name, $surname, $email, $positionId, $roleId,
            $gender, $age, $experience, $salary
        );
    }

    public function deleteWorkerById(int $id)
    {
       return $this->repositoryPool->worker()->delete($id);
    }

    public function selectDepartmentByName(string $name)
    {
        return $this->repositoryPool->department()->selectIdByName($name);
    }

    public function selectWorkerPersonalInformationById(int $workerId)
    {
        return $this->repositoryPool->worker()->selectFullWithPhoto($workerId);
    }

    public function selectPositionIdNameByWorkerId(int $workerId)
    {
        return $this->repositoryPool->position()->selectByWorkerId($workerId);
    }

    public function selectRoleIdNameByWorkerId(int $workerId)
    {
        return $this->repositoryPool->role()->selectByWorkerId($workerId);
    }

    public function updateWorkerPersonalInfoById(
        int $id, string $name, string $surname, string $email, string $gender,
        int $age, $experience, string $description
    ) {
        return $this->repositoryPool->worker()->update(
            $id, $name, $surname, $email, $gender, $age, $experience, $description
        );
    }

    public function updateWorkerMainPhotoByWorkerId(int $workerId, string $filename)
    {
        return $this->repositoryPool->worker()->updatePhoto($workerId, $filename);
    }

    public function selectWorkerMainPhotoByWorkerId(int $workerId)
    {
        return $this->repositoryPool->worker()->selectPhoto($workerId);
    }

    public function selectWorkerSocialNetworksByWorkerId(int $workerId)
    {
        return $this->repositoryPool->worker()->selectSocials($workerId);
    }

    public function updateWorkerSocialById(int $id, array $data)
    {
        return $this->repositoryPool->worker()->updateSocials($id, $data);
    }

    public function selectServicesInDepartmentByWorkerId(int $workerId)
    {
        return $this->repositoryPool->service()->selectAllInWorkerDepartment($workerId);
    }

    public function selectDepartmentsByWorkerId(int $workerId)
    {
        return $this->repositoryPool->department()->selectAllInWorkerDepartment($workerId);
    }

    public function selectUsersByEmailPart(string $emailPart)
    {
        return $this->repositoryPool->user()->selectByEmailPart($emailPart);
    }
}