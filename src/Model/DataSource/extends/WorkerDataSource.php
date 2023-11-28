<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\WorkerWriteDTO;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersSetting;
use Src\Model\Table\WorkersSocial;


class WorkerDataSource extends DataSource
{
    public function __construct(IDatabase $db)
    {
        parent::__construct($db);
    }

    public function selectWorkerByEmail(string $email)
    {
        $id = Workers::$id;

        $builder = new SqlBuilder($this->db);
        $builder->select([Workers::$id])
                ->from(Workers::$table)
                ->whereEqual(Workers::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            /**
             * workers.id -> id
             */
            return $result[explode('.', $id)[1]];
        }
        return false;
    }

    public function insertWorker(WorkerWriteDTO $worker)
    {
        $currentDatetime = date('Y-m-d H:i:s');

        $builder = new SqlBuilder($this->db);
        $builder->insertInto(Workers::$table,
            [
                Workers::$name, Workers::$surname, Workers::$password, Workers::$email,
                Workers::$gender, Workers::$age, Workers::$years_of_experience,
                Workers::$position_id, Workers::$salary, Workers::$role_id,
                Workers::$created_date
            ]
        )->values(
            [':name', ':surname', ':password', ':email',
             ':gender', ':age', ':experience', ':position_id',
             ':salary', ':role_id', ':created_date'],
            [
                $worker->getName(), $worker->getSurname(), $worker->getPassword(),
                $worker->getEmail(), $worker->getGender(), $worker->getAge(),
                $worker->getYearsOfExperience(), $worker->getPositionId(),
                $worker->getSalary(), $worker->getRoleId(), $currentDatetime
            ]
        )->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertWorkerSettings(int $workerId, string $recoveryCode) {
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(WorkersSetting::$table,
                    [
                        WorkersSetting::$worker_id,
                        WorkersSetting::$recovery_code
                    ]
                )->values([':worker_id', ':recovery_code'], [$workerId, $recoveryCode])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertWorkerSocial(int $workerId) {
        $builder = new SqlBuilder($this->db);
        $builder->insertInto(WorkersSocial::$table, [WorkersSocial::$worker_id])
                ->values([':worker_id'], [$workerId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
    public function updateWorkerSettingDateOfSendingRecoveryCode(
        int $id, string $recoveryCode
    ) {
        $currentDateTime = date("Y-m-d H:i:s");

        $builder = new SqlBuilder($this->db);
        $builder->update(WorkersSetting::$table)
                ->set(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
                ->andSet(WorkersSetting::$date_of_sending, ':date_of_sending', $currentDateTime)
                ->whereEqual(WorkersSetting::$id, ':id', $id)
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}