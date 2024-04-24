<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\Repository\impl\extend\AdminRepository;
use Src\Model\Repository\impl\extend\AffiliateRepository;
use Src\Model\Repository\impl\extend\DepartmentRepository;
use Src\Model\Repository\impl\extend\OrderRepository;
use Src\Model\Repository\impl\extend\PositionRepository;
use Src\Model\Repository\impl\extend\RoleRepository;
use Src\Model\Repository\impl\extend\ServiceRepository;
use Src\Model\Repository\impl\extend\WorkerRepository;

class AdminDataSource extends WorkerDataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectAllAdminsRows()
    {
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->selectCount();
    }

    public function selectAdminIdByEmail(string $email)
    {
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->selectIdByEmail($email);
    }

    public function insertAdmin(AdminWriteDTO $admin)
    {
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->insert($admin);
    }

    public function insertAdminSetting(int $adminId)
    {
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->insertSettings($adminId);
    }

    public function updateAdmin(AdminWriteDTO $admin)
    {
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->update($admin);
    }

    public function selectAdminPasswordByEmail(string $email)
    {
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->selectPasswordByEmail($email);
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
        $adminRepository = AdminRepository::getInstance();

        return $adminRepository->selectProfile($adminId);
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
        $workerRepository = WorkerRepository::getInstance();

        return $workerRepository->selectAll($limit, $offset, $orderByField, $orderDirection);
    }

    public function selectAllPositions()
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->selectAll();
    }

    public function selectAllRoles()
    {
        $roleRepository = RoleRepository::getInstance();

        return $roleRepository->selectAll();
    }

    public function selectServicesAllByDepartmentId(int $departmentId)
    {
        $serviceRepository = ServiceRepository::getInstance();

        return $serviceRepository->selectAllByDepartmentId($departmentId);
    }

    public function selectDepartmentsAllForAdmin(
        int $limit, int $offset,
        string $orderByField = 'departments.id', string $orderDirection = 'asc'
    ) {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertDepartment(
        string $name, string $description, string $photoFilename
    ){
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->insert($name, $description, $photoFilename);
    }

    public function updateDepartment(int $id, string $name, string $description)
    {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->update($id, $name, $description);
    }

    public function selectDepartmentPhotoById(int $id)
    {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->selectPhoto($id);
    }

    public function updateDepartmentPhotoById(int $id, string $photoFilename)
    {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->updatePhoto($id, $photoFilename);
    }

    public function deleteDepartmentById(int $id)
    {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->delete($id);
    }

    public function selectFutureOrdersByDepartmentId(int $departmentId)
    {
       $orderRepository = OrderRepository::getInstance();

       return $orderRepository->selectAllUpcomingByDepartmentId($departmentId);
    }

    public function selectFutureOrdersByWorkerId(int $workerId)
    {
        $orderRepository = OrderRepository::getInstance();

        return $orderRepository->selectAllUpcomingByWorkerId($workerId);
    }

    public function selectPositionsAllWithDepartments(
        int $limit, int $offset,
        string $orderByField = 'positions.id', string $orderDirection = 'asc'
    ){
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertPosition(string $name, int $departmentId)
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->insert($name, $departmentId);
    }

    public function selectPositionIdByNameAndDepartment(string $name, int $departmentId)
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->selectIdByNameAndDepartment($name, $departmentId);
    }

    public function selectPositionById(int $id)
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->select($id);
    }

    public function updatePositionById(int $id, string $name, int $departmentId)
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->update($id, $name, $departmentId);
    }

    public function selectPositionWithDepartmentById(int $id)
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->selectWithDepartment($id);
    }

    public function selectFutureOrdersByPositionId(int $positionId)
    {
        $orderRepository = OrderRepository::getInstance();

        return $orderRepository->selectAllUpcomingByPositionId($positionId);
    }

    public function deletePositionById(int $id)
    {
        $positionRepository = PositionRepository::getInstance();

        return $positionRepository->delete($id);
    }

    public function selectAllAffiliatesForAdminTable(
        int $limit, int $offset,
        string $orderByField = 'affiliates.id', string $orderDirection = 'asc'
    ){
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertAffiliate(
        string $name, string $country, string $city, string $address, ?int $managerId = null
    ) {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->insert($name, $country, $city, $address, $managerId);
    }

    public function selectAffiliateById(int $id)
    {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->select($id);
    }

    public function selectAffiliateByIdForTable(int $id)
    {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->selectRow($id);
    }

    public function selectAffiliateByAddressAndNotId(
        int $id, string $country, string $city, string $address
    ) {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->selectIdByAddressAndNotId($id, $country, $city, $address);
    }

    public function selectAffiliateByCountryCityAndAddress(
        string $country, string $city, string $address
    ) {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->selectIdByAddress($country, $city, $address);
    }

    public function updateAffiliateById(
        int $id, string $name, string $country,
        string $city, string $address, ?int $managerId = null
    ) {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->update($id, $name, $country, $city, $address, $managerId);
    }

    public function selectFutureOrdersByAffiliateId(int $affiliateId)
    {
        $orderRepository = OrderRepository::getInstance();

        return $orderRepository->selectAllUpcomingByAffiliateId($affiliateId);
    }

    public function deleteAffiliateById(int $id)
    {
        $affiliateRepository = AffiliateRepository::getInstance();

        return $affiliateRepository->delete($id);
    }

    public function selectWorkersByDepartmentId(
        int $departmentId, int $limit, int $offset
    ) {
        $workerRepository = WorkerRepository::getInstance();

        return $workerRepository->selectAllLimitedByDepartmentId($departmentId, $limit, $offset);
    }

    public function selectWorkersByServiceId(
        int $serviceId, int $limit, int $offset
    ){
        $workerRepository = WorkerRepository::getInstance();

        return $workerRepository->selectAllLimitedByServiceId($serviceId, $limit, $offset);
    }

    public function selectDepartmentFullById(int $id)
    {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->selectWithPhoto($id);
    }

    public function selectAllServicesWithDepartmentsInfo(
        int $limit, int $offset,
        string $orderByField = 'services.id', string $orderDirection = 'asc'
    ){
        $serviceRepository = ServiceRepository::getInstance();

        return $serviceRepository->selectAllLimited(
            $limit, $offset, $orderByField, $orderDirection
        );
    }
}