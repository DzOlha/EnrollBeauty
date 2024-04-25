<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Admins;
use Src\Model\Table\AdminsSetting;
use Src\Model\Table\Roles;

class AdminRepository extends Repository
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

    public function selectCount(): int | false
    {
        $this->builder->selectCount('*', 'number')
            ->from(Admins::$table)
            ->build();

        $result = $this->db->singleRow();
        if($result) {
            return $result['number'];
        }
        return $result;
    }

    public function selectIdByEmail(string $email): int | false
    {
        $this->builder->select([Admins::$id])
            ->from(Admins::$table)
            ->whereEqual(Admins::$email, ':email', $email)
            ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // admins.id -> id
            $key = explode('.', Admins::$id)[1];
            return $result[$key];
        }
        return $result;
    }


    public function insert(AdminWriteDTO $admin): int | false
    {
        $currentDatetime = date('Y-m-d H:i:s');
        $this->builder->insertInto(Admins::$table,
            [Admins::$name, Admins::$surname, Admins::$password,
             Admins::$email, Admins::$created_date, Admins::$role_id])
            ->values(
                [':name', ':surname', ':password', ':email', ':created_datetime'],
                [$admin->getName(), $admin->getSurname(), $admin->getPasswordHash(),
                 $admin->getEmail(), $currentDatetime],
                true
            )   ->subqueryBegin()
            ->select([Roles::$id])
            ->from(Roles::$table)
            ->whereEqual(Roles::$name, ':admin_role', $admin->getRole())
            ->subqueryEnd()
            ->queryEnd()
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertSettings(int $adminId): int | false
    {
        $this->builder->insertInto(AdminsSetting::$table, [AdminsSetting::$admin_id])
            ->values([':admin_id'], [$adminId])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }


    public function update(AdminWriteDTO $admin): bool
    {
        $this->builder->update(Admins::$table)
            ->set(Admins::$name, ':name', $admin->getName())
            ->andSet(Admins::$surname, ':surname', $admin->getSurname())
            ->andSet(Admins::$email, ':email', $admin->getEmail())
            ->andSet(Admins::$password, ':password', $admin->getPasswordHash())
            ->andSet(Admins::$status, ':status', $admin->getStatus())
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectPasswordByEmail(string $email): string | false
    {
        $this->builder->select([Admins::$password])
            ->from(Admins::$table)
            ->whereEqual(Admins::$email, ':email', $email)
            ->build();

        $this->db->bind(':email', $email);

        $result = $this->db->singleRow();
        if ($result) {
            // admins.password -> password
            $key = explode('.', Admins::$password)[1];
            return $result[$key];
        }
        return false;
    }

    /**
     * @param int $adminId
     * @return array | false
     * Example:
     * [
     *     id =>
     *     name =>
     *     surname =>
     *     email =>
     * ]
     */
    public function selectProfile(int $adminId): array | false
    {
        $this->builder->select([Admins::$id, Admins::$name, Admins::$surname, Admins::$email])
            ->from(Admins::$table)
            ->whereEqual(Admins::$id, ':id', $adminId)
        ->build();

        return $this->db->singleRow();
    }
}