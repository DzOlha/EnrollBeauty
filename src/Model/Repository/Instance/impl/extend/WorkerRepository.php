<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DTO\Write\WorkerWriteDTO;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Positions;
use Src\Model\Table\Workers;
use Src\Model\Table\WorkersPhoto;
use Src\Model\Table\WorkersServicePricing;
use Src\Model\Table\WorkersServiceSchedule;
use Src\Model\Table\WorkersSetting;
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

    public function selectIdByEmail(string $email): int | false
    {
        $id = Workers::$id;

        $this->builder->select([Workers::$id])
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

    public function insert(WorkerWriteDTO $worker): int | false
    {
        $currentDatetime = date('Y-m-d H:i:s');

        $this->builder->insertInto(Workers::$table,
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
    public function insertSettings(int $id, string $recoveryCode): int | false
    {
        $this->builder->insertInto(WorkersSetting::$table,
            [
                WorkersSetting::$worker_id,
                WorkersSetting::$recovery_code
            ]
        )->values([':worker_id', ':recovery_code'], [$id, $recoveryCode])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertSocials(int $id): bool
    {
        $this->builder->insertInto(WorkersSocial::$table, [WorkersSocial::$worker_id])
            ->values([':worker_id'], [$id])
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function insertPhoto(int $id, int $isMain = 0): bool
    {
        $this->builder->insertInto(WorkersPhoto::$table,
            [WorkersPhoto::$worker_id, WorkersPhoto::$is_main])
            ->values([':worker_id', ':is_main'], [$id, $isMain])
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updateRecoveryCodeSendingDate(int $id, string $recoveryCode): bool
    {
        $currentDateTime = date("Y-m-d H:i:s");

        $this->builder->update(WorkersSetting::$table)
            ->set(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
            ->andSet(WorkersSetting::$date_of_sending, ':date_of_sending', $currentDateTime)
            ->whereEqual(WorkersSetting::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectRecoveryCodeSendingDate(string $recoveryCode): string | false
    {
        $this->builder->select([WorkersSetting::$date_of_sending])
            ->from(WorkersSetting::$table)
            ->whereEqual(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
        ->build();

        $result = $this->db->singleRow();
        if ($result) {
            return $result[explode('.', WorkersSetting::$date_of_sending)[1]];
        } else {
            return false;
        }
    }

    public function updatePassword(string $recoveryCode, string $passwordHash): bool
    {
        $this->builder->update(Workers::$table)
            ->innerJoin(WorkersSetting::$table)
            ->on(Workers::$id, WorkersSetting::$worker_id)
            ->set(Workers::$password, ':password', $passwordHash)
            ->whereEqual(WorkersSetting::$recovery_code, ':recovery_code', $recoveryCode)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectPasswordByEmail(string $email): string | false
    {
        $this->builder->select([Workers::$password])
            ->from(Workers::$table)
            ->whereEqual(Workers::$email, ':email', $email)
        ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // workers.password -> password
            return $result[explode('.', Workers::$password)[1]];
        } else {
            return false;
        }
    }

    public function updateRecoveryCodeToNull(string $recoveryCode): bool
    {
        $this->builder->update(WorkersSetting::$table)
            ->setNull(WorkersSetting::$recovery_code)
            ->whereEqual(
                WorkersSetting::$recovery_code,
                ':recovery_code',
                $recoveryCode
            )
            ->build();
        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return array|false
     *
     * [ id =>, name =>, surname =>, email =>, filename =>, position => ]
     */
    public function selectWithPhoto(int $id): array | false
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname,
                                Workers::$email, WorkersPhoto::$filename, Positions::$name],
            [Positions::$name => 'position'])
            ->from(Workers::$table)
            ->leftJoin(WorkersPhoto::$table)
            ->on(Workers::$id, WorkersPhoto::$worker_id)
            ->innerJoin(Positions::$table)
            ->on(Workers::$position_id, Positions::$id)
            ->whereEqual(Workers::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    /**
     * [ id =>, name =>, surname =>, email =>, position_id =>, role_id =>,
     *   gender =>, age =>, years_of_experience =>, salary => ]
     */
    public function select(int $id): array | false
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname, Workers::$email,
                                Workers::$position_id, Workers::$role_id, Workers::$gender,
                                Workers::$age, Workers::$years_of_experience, Workers::$salary])
            ->from(Workers::$table)
            ->whereEqual(Workers::$id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function selectAllByEmailNotId(
        int $id, string $email
    ): bool
    {
        $this->builder->select([Workers::$id])
            ->from(Workers::$table)
            ->whereEqual(Workers::$email, ':email', $email)
            ->andNotEqual(Workers::$id, ':id', $id)
        ->build();

        $result = $this->db->singleRow();
        if($result) {
            return true;
        }
        return false;
    }

    /**
     * [ id =>, name =>, surname =>, email =>, position =>, salary =>, years_of_experience => ]
     */
    public function selectRow(int $id): array | false
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname, Workers::$email,
                                Positions::$name, Workers::$salary, Workers::$years_of_experience],
            [
                Positions::$name => 'position',
                Workers::$years_of_experience => 'experience'
            ])
            ->from(Workers::$table)
            ->innerJoin(Positions::$table)
                ->on(Workers::$position_id, Positions::$id)
            ->whereEqual(Workers::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function updateFull(
        int $id, string $name, string $surname, string $email,
        int $positionId, int $roleId, ?string $gender, int $age,
        float $experience, ?float $salary
    ): bool
    {
        $this->builder->update(Workers::$table)
            ->set(Workers::$name, ':name', $name)
            ->andSet(Workers::$surname, ':surname', $surname)
            ->andSet(Workers::$email, ':email', $email)
            ->andSet(Workers::$position_id, ":position_id", $positionId)
            ->andSet(Workers::$role_id, ':role_id', $roleId)
            ->andSet(Workers::$gender, ':gender', $gender)
            ->andSet(Workers::$age, ':age', $age)
            ->andSet(Workers::$years_of_experience, ':years', $experience)
            ->andSet(Workers::$salary, ':salary', $salary)
            ->whereEqual(Workers::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $this->builder->delete()
            ->from(Workers::$table)
            ->whereEqual(Workers::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [ id =>, name =>, surname =>, email =>, position_id =>, role_id =>,
     *   gender =>, age =>, years_of_experience =>, salary =>,
     *   description =>, filename => ]
     */
    public function selectFullWithPhoto(int $id)
    {
        $this->builder->select([Workers::$id, Workers::$name, Workers::$surname, Workers::$email,
                                Workers::$position_id, Workers::$role_id, Workers::$gender,
                                Workers::$age, Workers::$years_of_experience, Workers::$salary,
                                Workers::$description, WorkersPhoto::$filename])
            ->from(Workers::$table)
            ->leftJoin(WorkersPhoto::$table)
                ->on(Workers::$id, WorkersPhoto::$worker_id)
            ->whereEqual(Workers::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function update(
        int $id, string $name, string $surname, string $email, string $gender,
        int $age, $experience, string $description
    ): bool
    {
        $this->builder->update(Workers::$table)
            ->set(Workers::$name, ':name', $name)
            ->andSet(Workers::$surname, ':surname', $surname)
            ->andSet(Workers::$email, ':email', $email)
            ->andSet(Workers::$gender, ':gender', $gender)
            ->andSet(Workers::$age, ':age', $age)
            ->andSet(Workers::$years_of_experience, ":experience", $experience)
            ->andSet(Workers::$description, ':description', $description)
            ->whereEqual(Workers::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function updatePhoto(int $id, string $filename): bool
    {
        $isMain = 1;
        $this->builder->update(WorkersPhoto::$table)
            ->set(WorkersPhoto::$filename, ':filename', $filename)
            ->whereEqual(WorkersPhoto::$worker_id, ':id', $id)
            ->andEqual(WorkersPhoto::$is_main, ':is_main', $isMain)
            ->limit(1)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectPhoto(int $id): string | false
    {
        $isMain = 1;
        $this->builder->select([WorkersPhoto::$filename])
            ->from(WorkersPhoto::$table)
            ->whereEqual(WorkersPhoto::$worker_id, ':worker_id', $id)
            ->andEqual(WorkersPhoto::$is_main, ':is_main', $isMain)
        ->build();

        $result = $this->db->singleRow();
        if($result) {
            // workers_photo.filename -> filename
            return $result[explode('.', WorkersPhoto::$filename)[1]];
        }
        return $result;
    }

    /**
     * [ id =>, Instagram =>, TikTok =>, Facebook =>,
     *   LinkedIn =>, Github =>, YouTube =>, Telegram => ]
     */
    public function selectSocials(int $workerId): array | false
    {
        $this->builder->select([WorkersSocial::$id, WorkersSocial::$Instagram,
                                WorkersSocial::$TikTok, WorkersSocial::$Facebook, WorkersSocial::$LinkedIn,
                                WorkersSocial::$Github, WorkersSocial::$YouTube, WorkersSocial::$Telegram])
            ->from(WorkersSocial::$table)
            ->whereEqual(WorkersSocial::$worker_id, ':worker_id', $workerId)
        ->build();

        return $this->db->singleRow();
    }

    public function updateSocials(int $id, array $data): bool
    {
        $this->builder->update(WorkersSocial::$table)
            ->set(WorkersSocial::$Instagram, ':instagram', $data['Instagram'])
            ->andSet(WorkersSocial::$Facebook, ':facebook', $data['Facebook'])
            ->andSet(WorkersSocial::$TikTok, ':tiktok', $data['TikTok'])
            ->andSet(WorkersSocial::$Telegram, ':telegram', $data['Telegram'])
            ->andSet(WorkersSocial::$YouTube, ':youtube', $data['YouTube'])
            ->andSet(WorkersSocial::$LinkedIn, ':linkedin', $data['LinkedIn'])
            ->andSet(WorkersSocial::$Github, ':github', $data['Github'])
            ->whereEqual(WorkersSocial::$id, ':id', $id)
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }
}