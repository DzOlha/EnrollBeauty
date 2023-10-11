<?php

namespace Controller;

use Model\DataMapper\DataMapper;

abstract class AbstractController
{
    protected DataMapper $dataMapper;

    abstract public function getTypeDataMapper(): DataMapper;

    public function dataMapper(): DataMapper
    {
        return $this->getTypeDataMapper();
    }
}