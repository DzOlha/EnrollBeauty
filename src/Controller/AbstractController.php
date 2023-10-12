<?php

namespace Src\Controller;

use Src\Model\DataMapper\DataMapper;

abstract class AbstractController
{
    protected DataMapper $dataMapper;

    abstract public function getTypeDataMapper(): DataMapper;

    public function dataMapper(): DataMapper
    {
        return $this->getTypeDataMapper();
    }
}