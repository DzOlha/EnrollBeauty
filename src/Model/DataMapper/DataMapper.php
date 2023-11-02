<?php

namespace Src\Model\DataMapper;

use Src\Model\DataSource\DataSource;

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

    public function selectUserPasswordByEmail(string $email)
    {
        return $this->dataSource->selectUserPasswordByEmail($email);
    }

    public function selectUserIdByEmail(string $email)
    {
        return $this->dataSource->selectUserIdByEmail($email);
    }

    public function selectWorkersForService(int $serviceId)
    {
        return $this->dataSource->selectWorkersForService($serviceId);
    }

    public function selectServicesForWorker(int $workerId)
    {
        return $this->dataSource->selectServicesForWorker($workerId);
    }

    public function selectAllServices()
    {
        return $this->dataSource->selectAllServices();
    }

    public function selectAllWorkers()
    {
        return $this->dataSource->selectAllWorkers();
    }

    public function selectAllAffiliates()
    {
        return $this->dataSource->selectAllAffiliates();
    }
}