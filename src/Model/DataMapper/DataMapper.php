<?php

namespace Model\DataMapper;

use Model\DataSource\DataSource;

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
}