<?php

namespace Src\Model\Repository\Instance\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\DTO\Read\UserSocialReadDto;
use Src\Model\DTO\Write\UserWriteDto;
use Src\Model\Repository\Instance\impl\Repository;
use Src\Model\Table\Roles;
use Src\Model\Table\Users;
use Src\Model\Table\UsersPhoto;
use Src\Model\Table\UsersSetting;
use Src\Model\Table\UsersSocial;

class UserRepository extends Repository
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

    public function selectPasswordByEmail(string $email): string | false
    {
        $this->builder->select([Users::$password])
            ->from(Users::$table)
            ->whereEqual(Users::$email, ':email', $email)
        ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // users.password -> password
            $key = explode('.', Users::$password)[1];
            return $result[$key];
        }
        return false;
    }

    public function selectIdByEmail(string $email): int | false
    {
        $this->builder->select([Users::$id])
            ->from(Users::$table)
            ->whereEqual(Users::$email, ':email', $email)
        ->build();

        $result = $this->db->singleRow();
        if ($result) {
            // users.id -> id
            $key = explode('.', Users::$id)[1];
            return $result[$key];
        }
        return false;
    }

    /**
     * @param int $id
     * @return UserReadDto|false
     *
     * [ 'id' =>, 'name' =>, 'surname' =>, 'email' =>, 'filename' => ]
     */
    public function selectWithPhoto(int $id): array | false
    {
        $this->builder->select(
            [Users::$id, Users::$name, Users::$surname,
             Users::$email, UsersPhoto::$name]
        )
            ->from(Users::$table)
            ->leftJoin(UsersPhoto::$table)
                ->on(Users::$id, UsersPhoto::$user_id)
            ->whereEqual(Users::$id, ':id', $id)
        ->build();

        return $this->db->singleRow();
    }

    public function insert(UserWriteDto $user): int | false
    {
        $userRole = \Src\Model\Entity\Roles::$USER;

        $currentDatetime = date('Y-m-d H:i:s');
        $this->builder->insertInto(Users::$table,
            [
                Users::$name, Users::$surname, Users::$password, Users::$email,
                Users::$created_date, Users::$role_id
            ]
        )->values(
            [':name', ':surname', ':password', ':email', ':created_date'],
            [$user->getName(), $user->getSurname(), $user->getPasswordHash(),
             $user->getEmail(), $currentDatetime],
            true
        )->subqueryBegin()
            ->select([Roles::$id])
            ->from(Roles::$table)
            ->whereEqual(Roles::$name, ':user_role', $userRole)
        ->subqueryEnd()
        ->queryEnd()
        ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertSettings(int $id): int | false
    {
        $this->builder->insertInto(UsersSetting::$table, [UsersSetting::$user_id])
            ->values([':user_id'], [$id])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertSocials(int $id): int | false
    {
        $this->builder->insertInto(UsersSocial::$table, [UsersSocial::$user_id])
            ->values([':user_id'], [$id])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    public function insertPhoto(int $id, int $isMain = 0): int | false
    {
        $this->builder->insertInto(UsersPhoto::$table, [UsersPhoto::$user_id, UsersPhoto::$is_main])
            ->values([':user_id', ':is_main'], [$id, $isMain])
            ->build();

        if ($this->db->affectedRowsCount() > 0) {
            return $this->db->lastInsertedId();
        }
        return false;
    }

    /**
     * @param int $id
     * @return UserSocialReadDto|false
     *
     * [ id =>, user_id =>, YouTube =>, TikTok =>, Facebook =>, Instagram => ]
     */
    public function selectSocials(int $id): array | false
    {
        $this->builder->select([UsersSocial::$id, UsersSocial::$user_id,
                                UsersSocial::$YouTube, UsersSocial::$TikTok,
                                UsersSocial::$Facebook, UsersSocial::$Instagram])
            ->from(UsersSocial::$table)
            ->whereEqual(UsersSocial::$user_id, ':id', $id)
            ->build();

        return $this->db->singleRow();
    }

    public function selectEmail(int $id): string | false
    {
        $email = Users::$email;

        $this->builder->select([Users::$email])
            ->from(Users::$table)
            ->whereEqual(Users::$id, ':user_id', $id)
        ->build();

        $result = $this->db->singleRow();
        if($result) {
            /**
             * users.email -> email
             */
            $emailColumn = explode('.', $email)[1];
            return $result[$emailColumn];
        }
        return $result;
    }

    public function updateSocials(int $id, array $socials): bool
    {
        $this->builder->update(UsersSocial::$table)
            ->set(UsersSocial::$Instagram, ':Instagram', $socials['Instagram'])
            ->andSet(UsersSocial::$TikTok, ':TikTok', $socials['TikTok'])
            ->andSet(UsersSocial::$Facebook, ':Facebook', $socials['Facebook'])
            ->andSet(UsersSocial::$YouTube, ':YouTube', $socials['YouTube'])
            ->whereEqual(UsersSocial::$id, ':id', $id)
            ->build();

        if($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function update(
        int $id, string $name, string $surname, string $email
    ): bool
    {
        $this->builder->update(Users::$table)
            ->set(Users::$name, ':name', $name)
            ->andSet(Users::$surname, ':surname', $surname)
            ->andSet(Users::$email, ':email', $email)
            ->whereEqual(Users::$id, ':id', $id)
        ->build();

        if($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    public function selectPhoto(int $id): string | false
    {
        $this->builder->select([UsersPhoto::$name])
            ->from(UsersPhoto::$table)
            ->whereEqual(UsersPhoto::$user_id, ':user_id', $id)
            ->andEqual(UsersPhoto::$is_main, ':is_main', 1)
            ->build();

        $result = $this->db->singleRow();

        if($result) {
            // users_photo.filename -> filename
            return $result[explode('.', UsersPhoto::$name)[1]];
        }
        return $result;
    }

    public function updatePhoto(int $id, string $filename): bool
    {
        $this->builder->update(UsersPhoto::$table)
            ->set(UsersPhoto::$name, ':filename', $filename)
            ->whereEqual(UsersPhoto::$user_id, ':user_id', $id)
            ->andEqual(UsersPhoto::$is_main, ':is_main', 1)
        ->build();

        if($this->db->affectedRowsCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * [ id =>, email => ]
     */
    public function selectByEmailPart(string $emailPart): array | false
    {
        $this->builder->select([Users::$id, Users::$email])
            ->from(Users::$table)
            ->whereLikeInner(Users::$email, ':email', $emailPart)
        ->build();

        return $this->db->manyRows();
    }
}