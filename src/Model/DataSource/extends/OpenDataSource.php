<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\Repository\impl\extend\DepartmentRepository;
use Src\Model\Repository\impl\extend\ServicePricingRepository;
use Src\Model\Repository\impl\extend\WorkerRepository;

class OpenDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectWorkerPublicProfileById(int $id)
    {
        $workerRepository = WorkerRepository::getInstance();

        return $workerRepository->selectPublicProfile($id);
    }

    public function selectServicePricingAll()
    {
        $servicePricingRepository = ServicePricingRepository::getInstance();

        return $servicePricingRepository->selectAllMinPricelist();
    }

    public function selectDepartmentsFull(int $limit)
    {
        $departmentRepository = DepartmentRepository::getInstance();

        return $departmentRepository->selectAllLimitedWithPhoto($limit);
    }

    public function selectWorkersForHomepage(int $limit)
    {
        $workerRepository = WorkerRepository::getInstance();

        return $workerRepository->selectAllLimitedWithPhoto($limit);
    }
}