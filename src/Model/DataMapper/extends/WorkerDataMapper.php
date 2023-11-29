<?php

namespace Src\Model\DataMapper\extends;

use Src\Model\DataMapper\DataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Model\DTO\Write\WorkerWriteDTO;

class WorkerDataMapper extends DataMapper
{
    public function __construct(WorkerDataSource $ds)
    {
        parent::__construct($ds);
    }

    public function selectWorkerByEmail(string $email)
    {
        return $this->dataSource->selectWorkerByEmail($email);
    }

    public function insertWorker(WorkerWriteDTO $worker)
    {
        return $this->dataSource->insertWorker($worker);
    }

    public function insertWorkerSettings(int $workerId, string $recoveryCode)
    {
        return $this->dataSource->insertWorkerSettings($workerId, $recoveryCode);
    }

    public function insertWorkerSocial(int $workerId)
    {
        return $this->dataSource->insertWorkerSocial($workerId);
    }

    public function updateWorkerSettingDateOfSendingRecoveryCode(
        int $id, string $recoveryCode
    )
    {
        return $this->dataSource->updateWorkerSettingDateOfSendingRecoveryCode(
            $id, $recoveryCode
        );
    }

    public function selectWorkerDateSendingByRecoveryCode(
        string $recoveryCode
    )
    {
        return $this->dataSource->selectWorkerDateSendingByRecoveryCode($recoveryCode);
    }

    public function updateWorkerPasswordByRecoveryCode(
        string $recoveryCode, string $passwordHash
    )
    {
        return $this->dataSource->updateWorkerPasswordByRecoveryCode(
            $recoveryCode, $passwordHash
        );
    }

    public function selectWorkerPasswordByEmail(string $email)
    {
        return $this->dataSource->selectWorkerPasswordByEmail($email);
    }

    public function selectWorkerIdByEmail(string $email)
    {
        return $this->dataSource->selectWorkerIdByEmail($email);
    }

    public function updateRecoveryCodeByRecoveryCode(string $recoveryCode)
    {
        return $this->dataSource->updateRecoveryCodeByRecoveryCode($recoveryCode);
    }
}