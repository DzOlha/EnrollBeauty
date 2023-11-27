<?php

namespace Src\Model\DataSource\extends;

use Src\DB\IDatabase;
use Src\Model\DataSource\DataSource;
use Src\Model\DTO\Write\WorkerWriteDTO;
use Src\Model\Table\Roles;
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
        $workers = Workers::$table;
        $email_column = Workers::$email;
        $id = Workers::$id;

        $this->db->query("
            SELECT $id FROM $workers WHERE $email_column = :email
        ");
        $this->db->bind(':email', $email);

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
        $workers = Workers::$table;
        $_name = Workers::$name;
        $_surname = Workers::$surname;
        $_password = Workers::$password;
        $_email = Workers::$email;
        $_gender = Workers::$gender;
        $_age = Workers::$age;
        $_experience = Workers::$years_of_experience;
        $_position_id = Workers::$position_id;
        $_salary = Workers::$salary;
        $_role_id = Workers::$role_id;
        $_created_date = Workers::$created_date;


        $this->db->query(
            "INSERT INTO $workers (
                   $_name, $_surname, $_password, $_email,
                   $_gender, $_age, $_experience, $_position_id,
                   $_salary, $_role_id, $_created_date
                   )
                VALUES (:name, :surname, :password, :email, 
                        :gender, :age, :experience, :position_id,
                        :salary, :role_id, NOW())"
        );
        $this->db->bind(':name', $worker->getName());
        $this->db->bind(':surname', $worker->getSurname());
        $this->db->bind(':password', $worker->getPassword());
        $this->db->bind(':email', $worker->getEmail());
        $this->db->bind(':gender', $worker->getGender());
        $this->db->bind(':age', $worker->getAge());
        $this->db->bind(':experience', $worker->getYearsOfExperience());
        $this->db->bind(':position_id', $worker->getPositionId());
        $this->db->bind(':salary', $worker->getSalary());
        $this->db->bind(':role_id', $worker->getRoleId());

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertWorkerSettings(int $workerId, string $recoveryCode) {
        $workerSettings = WorkersSetting::$table;
        $_worker_id = WorkersSetting::$worker_id;
        $_recovery_code = WorkersSetting::$recovery_code;

        $this->db->query("
            INSERT INTO $workerSettings ($_worker_id, $_recovery_code)
            VALUES (:worker_id, :recovery_code)
        ");

        $this->db->bind(':worker_id', $workerId);
        $this->db->bind(':recovery_code', $recoveryCode);

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertWorkerSocial(int $workerId) {
        $workersSocial = WorkersSocial::$table;
        $_worker_id = WorkersSocial::$worker_id;

        $this->db->query("
            INSERT INTO $workersSocial ($_worker_id) VALUES (:worker_id)
        ");
        $this->db->bind(':worker_id', $workerId);
        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
    public function updateWorkerSettingDateOfSendingRecoveryCode(
        int $id, string $recoveryCode
    ) {
        $workersSetting = WorkersSetting::$table;
        $_id = WorkersSetting::$id;
        $_recovery_code = WorkersSetting::$recovery_code;
        $_date_of_sending = WorkersSetting::$date_of_sending;

        $this->db->query("
            UPDATE $workersSetting 
            SET $_recovery_code = :recovery_code, $_date_of_sending = NOW()
            WHERE $_id = :id
        ");
        $this->db->bind(':recovery_code', $recoveryCode);
        $this->db->bind(':id', $id);

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}