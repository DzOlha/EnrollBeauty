<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\Table\Positions;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersPhoto;
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
}