<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Model\DTO\Write\AdminWriteDTO;

class AdminDataMapper extends DataMapper
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
}