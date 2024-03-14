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
}