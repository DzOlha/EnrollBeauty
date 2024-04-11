<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\Table\Departments;
use Src\Model\Table\Positions;
use Src\Model\Table\Services;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersPhoto;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersSocial;

class OpenDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectWorkerPublicProfileById(int $id)
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

    public function selectServicePricingAll()
    {
        $workers_service_pricing = WorkersServicePricing::$table;
        $services = Services::$table;
        $departments = Departments::$table;

        $this->db->query("
            SELECT 
                d.id,
                d.name,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', s.id,
                        'name', s.name,
                        'min_price', min_price.min_price,
                        'currency', min_price.currency
                    )
                ) AS services
            FROM 
                $departments d
            INNER JOIN 
                $services s ON d.id = s.department_id
            INNER JOIN 
            (
                SELECT
                    service_id,
                    MIN(price) AS min_price,
                    currency
                FROM 
                    $workers_service_pricing
                GROUP BY 
                    service_id, currency
            ) min_price ON s.id = min_price.service_id
            GROUP BY 
                d.id

        ");

        return $this->db->manyRows();
    }

    public function selectDepartmentsFull(int $limit)
    {
        $this->builder->select([Departments::$id, Departments::$name,
                            Departments::$description, Departments::$photo_filename])
                    ->from(Departments::$table)
                    ->whereNotNull(Departments::$photo_filename)
                    ->andNotEmpty(Departments::$photo_filename)
                    ->limit($limit)
            ->build();

        return $this->db->manyRows();
    }

    public function selectWorkersForHomepage(int $limit)
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
}