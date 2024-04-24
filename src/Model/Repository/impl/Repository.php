<?php

namespace Src\Model\Repository\impl;

use Src\DB\Database\MySql;
use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\IRepository;

class Repository implements IRepository
{
    protected IDatabase $db;
    protected SqlBuilder $builder;
    protected static ?self $instance = null;

    public static function getInstance(
        IDatabase $db = null, SqlBuilder $builder = null
    ){
        if (self::$instance === null) {
            self::$instance = new self($db, $builder);
        }
        return self::$instance;
    }

    protected function __construct(
        IDatabase $db = null, SqlBuilder $builder = null
    ){
        $this->db = $db ?? MySql::getInstance();
        $this->builder = $builder ?? new SqlBuilder($this->db);
    }

    protected function _selectTotalRows(
        string $queryFrom, array $toBind
    ): int | false
    {
        $this->db->query(
            "SELECT COUNT(*) as totalRowsCount FROM $queryFrom"
        );
        $this->db->bindAll($toBind);

        $result = $this->db->singleRow();
        if($result) {
            return $result['totalRowsCount'];
        } else {
            return false;
        }
    }
    protected function _selectTotalSum(
        string $queryFrom, string $columnName, array $toBind
    ): int | false
    {
        $this->db->query(
            "SELECT SUM($columnName) as totalSum FROM $queryFrom"
        );
        $this->db->bindAll($toBind);

        $result = $this->db->singleRow();

        if ($result && isset($result['totalSum'])) {
            return $result['totalSum'];
        } else {
            return false;
        }
    }
    protected function _appendTotalRowsCount(
        string $queryFrom, array $result, array $toBind = []
    ): array | false
    {
        if($result) {
            $totalRowsCount = $this->_selectTotalRows($queryFrom, $toBind);
            if($totalRowsCount !== false) {
                $result += [
                    'totalRowsCount' => $totalRowsCount
                ];
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    protected function _appendTotalRowsSum(
        string $queryFrom, array $result, string $columnName, array $toBind = []
    ): array | false
    {
        if($result) {
            $totalRowsCount = $this->_selectTotalSum($queryFrom, $columnName, $toBind);
            if($totalRowsCount !== false) {
                $result += [
                    'totalSum' => $totalRowsCount
                ];
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}