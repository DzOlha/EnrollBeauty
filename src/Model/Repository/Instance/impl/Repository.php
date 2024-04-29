<?php

namespace Src\Model\Repository\Instance\impl;

use Src\DB\Database\MySql;
use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\IRepository;
use Src\Model\Table\Services;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;

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
    ): float | false
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

    protected function _serviceFilter($serviceId, $columnToJoin): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];

        if ($serviceId !== null && $serviceId !== '') {
            $result['where'] = " AND $columnToJoin = :service_id ";
            $result['toBind'] += [
                ':service_id' => $serviceId
            ];
        }
        return $result;
    }

    protected function _workerFilter($workerId, $columnToJoin): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];

        if ($workerId !== null && $workerId !== '') {
            $result['where'] = " AND $columnToJoin = :worker_id ";
            $result['toBind'] += [
                ':worker_id' => $workerId
            ];
        }
        return $result;
    }

    protected function _userFilter($userId, $columnToJoin): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];

        if ($userId !== null && $userId !== '') {
            $result['where'] = " AND $columnToJoin = :user_id ";
            $result['toBind'] += [
                ':user_id' => $userId
            ];
        }
        return $result;
    }

    protected function _statusFilter($status, $columnToJoin): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];

        if ($status !== null && $status !== '') {
            $result['where'] = " AND $columnToJoin = :status ";
            $result['toBind'] += [
                ':status' => $status
            ];
        }
        return $result;
    }

    protected function _affiliateFilter($affiliateId, $columnToJoin): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];

        if ($affiliateId !== null && $affiliateId !== '') {
            $result['where'] = " AND $columnToJoin = :affiliate_id ";
            $result['toBind'] += [
                ':affiliate_id' => $affiliateId
            ];
        }
        return $result;
    }

    protected function _dateFilter($dateFrom, $dateTo): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];
        $schedule_day = WorkersServiceSchedule::$day;

        $setFrom = ($dateFrom !== null && $dateFrom !== '');
        $setTo = ($dateTo !== null && $dateTo !== '');

        if($setFrom) {
            $result['where'] = " AND $schedule_day >= :date_from ";
            $result['toBind'] += [
                ':date_from' => $dateFrom
            ];
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_day <= :date_to ";
            $result['toBind'] += [
                ':date_to' => $dateTo
            ];
        }
        return $result;
    }

    protected function _timeFilter($timeFrom, $timeTo): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];
        $schedule_start_time = WorkersServiceSchedule::$start_time;
        $schedule_end_time = WorkersServiceSchedule::$end_time;
        $schedule_day = WorkersServiceSchedule::$day;

        $setFrom = ($timeFrom !== null && $timeFrom !== '');
        $setTo = ($timeTo !== null && $timeTo !== '');

        if($setFrom) {
            $result['where'] = " AND $schedule_start_time >= :time_from ";
            $result['toBind'] += [
                ':time_from' => $timeFrom
            ];
        } else {
            $result['where'] = " AND ($schedule_day > CURDATE() OR ($schedule_day = CURDATE() AND $schedule_start_time > CURTIME())) ";
        }
        if($setTo) {
            $result['where'] .= " AND $schedule_end_time <= :time_to ";
            $result['toBind'] += [
                ':time_to' => $timeTo
            ];
        }
        return $result;
    }

    protected function _priceFilter($priceFrom, $priceTo): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];
        $pricing_price = WorkersServicePricing::$price;

        $setFrom = ($priceFrom !== null && $priceFrom !== '');
        $setTo = ($priceTo !== null && $priceTo !== '');

        if($setFrom) {
            $result['where'] = " AND $pricing_price >= :price_from ";
            $result['toBind'] += [
                ':price_from' => $priceFrom
            ];
        }
        if($setTo) {
            $result['where'] .= " AND $pricing_price <= :price_to ";
            $result['toBind'] += [
                ':price_to' => $priceTo
            ];
        }
        return $result;
    }

    protected function _departmentFilter($departmentId): array
    {
        $result = [
            'where' => '',
            'toBind' => []
        ];
        $services_depId = Services::$department_id;

        $set = ($departmentId !== null && $departmentId !== '');
        if($set) {
            $result['where'] = " AND $services_depId = :department_id ";
            $result['toBind'] += [
                ':department_id' => $departmentId
            ];
        }
        return $result;
    }
}