<?php

namespace Src\Model\DTO\Write;

class AdminWriteDTO extends UserWriteDto
{

    public string $role = 'Admin';
    public ?int $status = 0;


    /**
     * @param $name
     * @param $surname
     * @param $email
     * @param $passwordHash
     * @param $role
     */
    public function __construct(
        $name, $surname, $email, $passwordHash, $role = null, $status = null
    )
    {
        parent::__construct($name, $surname, $email, $passwordHash);
        $this->role = $role;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

}