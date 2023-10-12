<?php

namespace Src\Model\DataSource;

use Src\DB\IDatabase;

abstract class DataSource
{
    protected ?IDatabase $db = null;

    public function __construct(IDatabase $db)
    {
        if (!$this->db) {
            $this->db = $db;
        }
    }

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }
    public function commitTransaction(): void
    {
        $this->db->commitTransaction();
    }
    public function rollBackTransaction(): void
    {
        $this->db->rollBackTransaction();
    }
}