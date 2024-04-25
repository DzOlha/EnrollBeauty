<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DTO\Write\AdminWriteDTO;

class AdminDataSource extends WorkerDataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectAllAdminsRows()
    {
        return $this->repositoryPool->admin()->selectCount();
    }

    public function selectAdminIdByEmail(string $email)
    {
        return $this->repositoryPool->admin()->selectIdByEmail($email);
    }

    public function insertAdmin(AdminWriteDTO $admin)
    {
        return $this->repositoryPool->admin()->insert($admin);
    }

    public function insertAdminSetting(int $adminId)
    {
        return $this->repositoryPool->admin()->insertSettings($adminId);
    }

    public function updateAdmin(AdminWriteDTO $admin)
    {
        return $this->repositoryPool->admin()->update($admin);
    }

    public function selectAdminPasswordByEmail(string $email)
    {
        return $this->repositoryPool->admin()->selectPasswordByEmail($email);
    }

    /**
     * @param int $adminId
     * @return false|array|null
     *
     * [
     *      'name' =>
     *      'surname' =>
     *      'email' =>
     * ]
     */
    public function selectAdminInfoById(int $adminId)
    {
        return $this->repositoryPool->admin()->selectProfile($adminId);
    }


    /**
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false|null
     *
     * [
     *      0 => [
     *          id =>
     *          name =>
     *          surname =>
     *          email =>
     *          position =>
     *          salary =>
     *          experience =>
     *      ]
     *      ....................
     * ]
     */
    public function selectAllWorkersForAdmin(
        int $limit, int $offset,
        string $orderByField = 'workers.id', string $orderDirection = 'asc'
    ) {
        return $this->repositoryPool->worker()->selectAllLimited($limit, $offset, $orderByField, $orderDirection);
    }

    public function selectAllPositions()
    {
        return $this->repositoryPool->position()->selectAll();
    }

    public function selectAllRoles()
    {
        return $this->repositoryPool->role()->selectAll();
    }

    public function selectServicesAllByDepartmentId(int $departmentId)
    {
        return $this->repositoryPool->service()->selectAllByDepartmentId($departmentId);
    }

    public function selectDepartmentsAllForAdmin(
        int $limit, int $offset,
        string $orderByField = 'departments.id', string $orderDirection = 'asc'
    ) {
        return $this->repositoryPool->department()->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertDepartment(
        string $name, string $description, string $photoFilename
    ){
        return $this->repositoryPool->department()->insert($name, $description, $photoFilename);
    }

    public function updateDepartment(int $id, string $name, string $description)
    {
        return $this->repositoryPool->department()->update($id, $name, $description);
    }

    public function selectDepartmentPhotoById(int $id)
    {
        return $this->repositoryPool->department()->selectPhoto($id);
    }

    public function updateDepartmentPhotoById(int $id, string $photoFilename)
    {
        return $this->repositoryPool->department()->updatePhoto($id, $photoFilename);
    }

    public function deleteDepartmentById(int $id)
    {
        return $this->repositoryPool->department()->delete($id);
    }

    public function selectFutureOrdersByDepartmentId(int $departmentId)
    {
       return $this->repositoryPool->orderService()->selectAllUpcomingByDepartmentId($departmentId);
    }

    public function selectFutureOrdersByWorkerId(int $workerId)
    {
        return $this->repositoryPool->orderService()->selectAllUpcomingByWorkerId($workerId);
    }

    public function selectPositionsAllWithDepartments(
        int $limit, int $offset,
        string $orderByField = 'positions.id', string $orderDirection = 'asc'
    ){
        return $this->repositoryPool->position()->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertPosition(string $name, int $departmentId)
    {
        return $this->repositoryPool->position()->insert($name, $departmentId);
    }

    public function selectPositionIdByNameAndDepartment(string $name, int $departmentId)
    {
        return $this->repositoryPool->position()->selectIdByNameAndDepartment($name, $departmentId);
    }

    public function selectPositionById(int $id)
    {
        return $this->repositoryPool->position()->select($id);
    }

    public function updatePositionById(int $id, string $name, int $departmentId)
    {
        return $this->repositoryPool->position()->update($id, $name, $departmentId);
    }

    public function selectPositionWithDepartmentById(int $id)
    {
        return $this->repositoryPool->position()->selectWithDepartment($id);
    }

    public function selectFutureOrdersByPositionId(int $positionId)
    {
        return $this->repositoryPool->orderService()->selectAllUpcomingByPositionId($positionId);
    }

    public function deletePositionById(int $id)
    {
        return $this->repositoryPool->position()->delete($id);
    }

    public function selectAllAffiliatesForAdminTable(
        int $limit, int $offset,
        string $orderByField = 'affiliates.id', string $orderDirection = 'asc'
    ){
        return $this->repositoryPool->affiliate()->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertAffiliate(
        string $name, string $country, string $city, string $address, ?int $managerId = null
    ) {
        return $this->repositoryPool->affiliate()->insert($name, $country, $city, $address, $managerId);
    }

    public function selectAffiliateById(int $id)
    {
        return $this->repositoryPool->affiliate()->select($id);
    }

    public function selectAffiliateByIdForTable(int $id)
    {
        return $this->repositoryPool->affiliate()->selectRow($id);
    }

    public function selectAffiliateByAddressAndNotId(
        int $id, string $country, string $city, string $address
    ) {
        return $this->repositoryPool->affiliate()->selectIdByAddressAndNotId($id, $country, $city, $address);
    }

    public function selectAffiliateByCountryCityAndAddress(
        string $country, string $city, string $address
    ) {
        return $this->repositoryPool->affiliate()->selectIdByAddress($country, $city, $address);
    }

    public function updateAffiliateById(
        int $id, string $name, string $country,
        string $city, string $address, ?int $managerId = null
    ) {
        return $this->repositoryPool->affiliate()->update($id, $name, $country, $city, $address, $managerId);
    }

    public function selectFutureOrdersByAffiliateId(int $affiliateId)
    {
        return $this->repositoryPool->orderService()->selectAllUpcomingByAffiliateId($affiliateId);
    }

    public function deleteAffiliateById(int $id)
    {
        return $this->repositoryPool->affiliate()->delete($id);
    }

    public function selectWorkersByDepartmentId(
        int $departmentId, int $limit, int $offset
    ) {
        return $this->repositoryPool->worker()
            ->selectAllLimitedByDepartmentId($departmentId, $limit, $offset);
    }

    public function selectWorkersByServiceId(
        int $serviceId, int $limit, int $offset
    ){
        return $this->repositoryPool->worker()
            ->selectAllLimitedByServiceId($serviceId, $limit, $offset);
    }

    public function selectDepartmentFullById(int $id)
    {
        return $this->repositoryPool->department()->selectWithPhoto($id);
    }

    public function selectAllServicesWithDepartmentsInfo(
        int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    ){
        return $this->repositoryPool->service()->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }
}