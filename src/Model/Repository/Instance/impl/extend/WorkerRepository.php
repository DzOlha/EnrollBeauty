<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Positions;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersPhoto;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersSocial;

class WorkerRepository extends Repository
{
    protected static ?Repository $instance = null;

    public static function getInstance(
        IDatabase $db = null, SqlBuilder $builder = null
    ){
        if (self::$instance === null) {
            self::$instance = new self($db, $builder);
        }
        return self::$instance;
    }
    /**
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return array|false|null
     *
     * [
     *      0 => [
     *          id =>
     *          name =>
     *          surname =>
     *          email =>
     *          position =>
     *          salary =>
     *          experience =>
     *      ]
     *      ....................
     *      totalRowsCount =>
     * ]
     */
    public function selectAllLimited(
        int $limit, int $offset,
        string $orderByField = 'workers.id', string $orderDirection = 'asc'
    ): array | false
    {
        $workers = Workers::$table;
        $_position_id = Workers::$position_id;
        $experience = Workers::$years_of_experience;

        $positions = Positions::$table;
        $position_id = Positions::$id;
        $position_name = Positions::$name;

        $queryFrom = "
            $workers INNER JOIN $positions ON $_position_id = $position_id
        ";

        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname, Workers::$email,
                                "$position_name as position", Workers::$salary, "$experience as experience"])
            ->from(Workers::$table)
            ->innerJoin(Positions::$table)
            ->on(Workers::$position_id, Positions::$id)
            ->orderBy($orderByField, $orderDirection)
            ->limit($limit)
            ->offset($offset)
            ->build();


        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result);
    }


    /**
     * @param int $departmentId
     * @param int $limit
     * @param int $offset
     * @return array|false
     *
     * [
     *      0 => [id =>, name =>, surname =>, email =>, filename =>, position => ]
     *      1 => [id =>, name =>, surname =>, email =>, filename =>, position => ]
     * ...........................................................................
     *      totalRowsCount =>
     * ]
     */
    public function selectAllLimitedByDepartmentId(
        int $departmentId, int $limit, int $offset
    ): array | false
    {
        $workers = Workers::$table;
        $workersPositionId = Workers::$position_id;

        $positions = Positions::$table;
        $positionsId = Positions::$id;
        $positionsDepartmentId = Positions::$department_id;

        $queryFrom = " $workers INNER JOIN $positions ON $workersPositionId = $positionsId 
                        WHERE $positionsDepartmentId = :department_id ";
        $params = [
            ':department_id' => $departmentId
        ];

        $isMain = 1;
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname,
                                Workers::$email, WorkersPhoto::$filename, Positions::$name],
            [Positions::$name => 'position'])
            ->from(Workers::$table)
            ->innerJoin(Positions::$table)
                ->on(Workers::$position_id, Positions::$id)
            ->leftJoin(WorkersPhoto::$table)
                ->on(Workers::$id, WorkersPhoto::$worker_id)
            ->whereEqual(Positions::$department_id, ':department_id', $departmentId)
            ->andEqual(WorkersPhoto::$is_main, ':is_main', $isMain)
            ->limit($limit)
            ->offset($offset)
        ->build();

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }

        return $this->_appendTotalRowsCount($queryFrom, $result, $params);
    }

    /**
     * @param int $serviceId
     * @param int $limit
     * @param int $offset
     * @return array|false
     *
     *  [
     *       0 => [id =>, name =>, surname =>, email =>, filename =>, position => ]
     *       1 => [id =>, name =>, surname =>, email =>, filename =>, position => ]
     *  ...........................................................................
     *       totalRowsCount =>
     *  ]
     */
    public function selectAllLimitedByServiceId(
        int $serviceId, int $limit, int $offset
    ): array | false
    {
        $workers = Workers::$table;
        $workersId = Workers::$id;

        $pricing = WorkersServicePricing::$table;
        $pricingServiceId = WorkersServicePricing::$service_id;
        $pricingWorkerId = WorkersServicePricing::$worker_id;

        $queryFrom = " $workers LEFT JOIN $pricing ON $workersId = $pricingWorkerId 
                        WHERE $pricingServiceId = :service_id ";
        $params = [
            ':service_id' => $serviceId
        ];

        $isMain = 1;
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname,
                                Workers::$email, WorkersPhoto::$filename, Positions::$name],
            [Positions::$name => 'position'])
            ->from(Workers::$table)
            ->innerJoin(Positions::$table)
                ->on(Workers::$position_id, Positions::$id)
            ->leftJoin(WorkersPhoto::$table)
                ->on(Workers::$id, WorkersPhoto::$worker_id)
            ->leftJoin(WorkersServicePricing::$table)
                ->on(Workers::$id, WorkersServicePricing::$worker_id)
            ->whereEqual(WorkersServicePricing::$service_id, ':service_id', $serviceId)
            ->andEqual(WorkersPhoto::$is_main, ':is_main', $isMain)
            ->limit($limit)
            ->offset($offset)
        ->build();

        $result = $this->db->manyRows();
        if($result == null) {
            return $result;
        }
        return $this->_appendTotalRowsCount($queryFrom, $result, $params);
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ id =>, name =>, surname =>, email =>, description =>,
     *  age =>, years_of_experience =>, position =>, filename =>,
     *  Instagram =>, TikTok =>, LinkedIn =>, Facebook =>,
     *  Github =>, Telegram =>, YouTube =>]
     */
    public function selectPublicProfile(int $id): array | false
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname,
                                Workers::$email, Workers::$description, Workers::$age,
                                Workers::$years_of_experience, Positions::$name, WorkersPhoto::$filename,
                                WorkersSocial::$Instagram, WorkersSocial::$TikTok,
                                WorkersSocial::$LinkedIn, WorkersSocial::$Facebook,
                                WorkersSocial::$Github, WorkersSocial::$Telegram,
                                WorkersSocial::$YouTube],
            [Positions::$name => 'position'])
            ->from(Workers::$table)
            ->innerJoin(Positions::$table)
                ->on(Workers::$position_id, Positions::$id)
            ->leftJoin(WorkersPhoto::$table)
                ->on(Workers::$id, WorkersPhoto::$worker_id)
            ->innerJoin(WorkersSocial::$table)
                ->on(Workers::$id, WorkersSocial::$worker_id)
            ->whereEqual(Workers::$id, ':id', $id)
            ->andEqual(WorkersPhoto::$is_main, ':is_main', 1)
        ->build();

        return $this->db->singleRow();
    }

    /**
     * @param int $limit
     * @return array|false
     *
     * [
     *      0 => [ id =>, name =>, surname =>, email =>, description =>,
     *             age =>, years_of_experience =>, position =>, filename =>,
     *             Instagram =>, TikTok =>, LinkedIn =>, Facebook =>,
     *             Github =>, Telegram =>, YouTube =>]
     * .......................................................................
     * ]
     */
    public function selectAllLimitedWithPhoto(int $limit)
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname,
                                Workers::$email, Workers::$description, Workers::$age,
                                Workers::$years_of_experience, Positions::$name, WorkersPhoto::$filename,
                                WorkersSocial::$Instagram, WorkersSocial::$TikTok,
                                WorkersSocial::$LinkedIn, WorkersSocial::$Facebook,
                                WorkersSocial::$Github, WorkersSocial::$Telegram,
                                WorkersSocial::$YouTube],
            [Positions::$name => 'position'])
            ->from(Workers::$table)
            ->innerJoin(Positions::$table)
                ->on(Workers::$position_id, Positions::$id)
            ->innerJoin(WorkersPhoto::$table)
                ->on(Workers::$id, WorkersPhoto::$worker_id)
            ->innerJoin(WorkersSocial::$table)
                ->on(Workers::$id, WorkersSocial::$worker_id)
            ->whereNotNull(WorkersPhoto::$filename)
            ->andEqual(WorkersPhoto::$is_main, ':is_main', 1)
            ->limit($limit)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @param int $serviceId
     * @return array|false
     * [
     *       0 => [
     *           'id' => ,
     *           'name' => ,
     *           'surname' => ,
     *       ]
     *  .......
     *  ]
     */
    public function selectAllByServiceId(int $serviceId): array | false
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname])
            ->from(WorkersServicePricing::$table)
            ->innerJoin(Workers::$table)
                ->on(WorkersServicePricing::$worker_id, Workers::$id)
            ->whereEqual(WorkersServicePricing::$service_id, ":id", $serviceId)
        ->build();

        return $this->db->manyRows();
    }

    /**
     * @return array|false
     * [
     *      0 => [
     *       'id' =>,
     *       'name' =>,
     *       'surname' =>
     *     ]
     *  ........
     *  ]
     */
    public function selectAll(): array | false
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname])
            ->from(Workers::$table)
        ->build();

        return $this->db->manyRows();
    }
}