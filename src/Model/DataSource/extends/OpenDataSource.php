<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;

class OpenDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectWorkerPublicProfileById(int $id)
    {
        return $this->repositoryPool->worker()->selectPublicProfile($id);
    }

    public function selectServicePricingAll()
    {
        return $this->repositoryPool->servicePricing()->selectAllMinPricelist();
    }

    public function selectDepartmentsFull(int $limit)
    {
        return $this->repositoryPool->department()->selectAllLimitedWithPhoto($limit);
    }

    public function selectWorkersForHomepage(int $limit)
    {
        return $this->repositoryPool->worker()->selectAllLimitedWithPhoto($limit);
    }
}