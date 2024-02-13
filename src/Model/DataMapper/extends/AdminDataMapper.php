<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Model\DTO\Write\AdminWriteDTO;

class AdminDataMapper extends WorkerDataMapper
{
    public function __construct(AdminDataSource $ds)
    {
        parent::__construct($ds);
    }

    public function selectAllAdminsRows()
    {
        return $this->dataSource->selectAllAdminsRows();
    }

    public function selectAdminIdByEmail(string $email)
    {
        return $this->dataSource->selectAdminIdByEmail($email);
    }

    public function insertAdmin(AdminWriteDTO $admin)
    {
        return $this->dataSource->insertAdmin($admin);
    }

    public function insertAdminSetting(int $adminId)
    {
        return $this->dataSource->insertAdminSetting($adminId);
    }

    public function updateAdmin(AdminWriteDTO $admin)
    {
        return $this->dataSource->updateAdmin($admin);
    }

    public function selectAdminPasswordByEmail(string $email)
    {
        return $this->dataSource->selectAdminPasswordByEmail($email);
    }

    public function selectAdminInfoById(int $adminId)
    {
        return $this->dataSource->selectAdminInfoById($adminId);
    }

    public function selectAllWorkersForAdmin(
        int    $limit, int $offset,
        string $orderByField = 'workers.id', string $orderDirection = 'asc'
    )
    {
        return $this->dataSource->selectAllWorkersForAdmin(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function selectAllPositions()
    {
        return $this->dataSource->selectAllPositions();
    }

    public function selectAllRoles()
    {
        return $this->dataSource->selectAllRoles();
    }

    public function selectServicesAllByDepartmentId(int $departmentId)
    {
        return $this->dataSource->selectServicesAllByDepartmentId($departmentId);
    }

    public function selectDepartmentsAllForAdmin(
        int $limit, int $offset,
        string $orderByField = 'departments.id', string $orderDirection = 'asc'
    ) {
        return $this->dataSource->selectDepartmentsAllForAdmin(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertDepartment(string $name)
    {
        return $this->dataSource->insertDepartment($name);
    }

    public function updateDepartmentName(int $id, string $name)
    {
        return $this->dataSource->updateDepartmentName($id, $name);
    }

    public function deleteDepartmentById(int $id)
    {
        return $this->dataSource->deleteDepartmentById($id);
    }

    public function selectFutureOrdersByDepartmentId(int $departmentId)
    {
        return $this->dataSource->selectFutureOrdersByDepartmentId($departmentId);
    }

    public function selectFutureOrdersByWorkerId(int $workerId)
    {
        return $this->dataSource->selectFutureOrdersByWorkerId($workerId);
    }

    public function selectPositionsAllWithDepartments(
        int $limit, int $offset,
        string $orderByField = 'positions.id', string $orderDirection = 'asc'
    ){
        return $this->dataSource->selectPositionsAllWithDepartments(
            $limit, $offset, $orderByField, $orderDirection
        );
    }

    public function insertPosition(string $name, int $departmentId)
    {
        return $this->dataSource->insertPosition($name, $departmentId);
    }

    public function selectPositionIdByNameAndDepartment(string $name, int $departmentId)
    {
        return $this->dataSource->selectPositionIdByNameAndDepartment($name, $departmentId);
    }

    public function selectPositionById(int $id)
    {
        return $this->dataSource->selectPositionById($id);
    }

    public function updatePositionById(int $id, string $name, int $departmentId)
    {
        return $this->dataSource->updatePositionById($id, $name, $departmentId);
    }

    public function selectPositionWithDepartmentById(int $id)
    {
        return $this->dataSource->selectPositionWithDepartmentById($id);
    }

    public function selectFutureOrdersByPositionId(int $positionId)
    {
        return $this->dataSource->selectFutureOrdersByPositionId($positionId);
    }

    public function deletePositionById(int $id)
    {
        return $this->dataSource->deletePositionById($id);
    }

    public function selectAllAffiliatesForAdminTable(
        int $limit, int $offset,
        string $orderByField = 'affiliates.id', string $orderDirection = 'asc'
    ){
        return $this->dataSource->selectAllAffiliatesForAdminTable(
            $limit, $offset, $orderByField, $orderDirection
        );
    }
}