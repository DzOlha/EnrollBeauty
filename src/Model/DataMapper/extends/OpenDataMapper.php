<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\OpenDataSource;

class OpenDataMapper extends DataMapper
{
    public function __construct(OpenDataSource $ds)
    {
        parent::__construct($ds);
    }

    public function selectWorkerPublicProfileById(int $id)
    {
        return $this->dataSource->selectWorkerPublicProfileById($id);
    }

    public function selectServicePricingAll()
    {
        return $this->dataSource->selectServicePricingAll();
    }

    public function selectDepartmentsFull(int $limit)
    {
        return $this->dataSource->selectDepartmentsFull($limit);
    }
}