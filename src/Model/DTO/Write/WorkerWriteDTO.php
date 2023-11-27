<?php

namespace Src\Model\DTO\Write;

class WorkerWriteDTO
{
    public string $name;
    public string $surname;
    public string $password;
    public string $email;
    public ?string $gender;
    public int $age;
    public float $years_of_experience;
    public int $position_id;
    public ?float $salary;
    public int $role_id;

    /**
     * @param string $name
     * @param string $surname
     * @param string $password
     * @param string $email
     * @param string|null $gender
     * @param int $age
     * @param float $years_of_experience
     * @param int $position_id
     * @param float|null $salary
     * @param int $role_id
     */
    public function __construct(
        string  $name, string $surname, string $password, string $email,
        ?string $gender, int $age, float $years_of_experience,
        int     $position_id, ?float $salary, int $role_id
    )
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
        $this->email = $email;
        $this->gender = $gender;
        $this->age = $age;
        $this->years_of_experience = $years_of_experience;
        $this->position_id = $position_id;
        $this->salary = $salary;
        $this->role_id = $role_id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     */
    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return float
     */
    public function getYearsOfExperience(): float
    {
        return $this->years_of_experience;
    }

    /**
     * @param float $years_of_experience
     */
    public function setYearsOfExperience(float $years_of_experience): void
    {
        $this->years_of_experience = $years_of_experience;
    }

    /**
     * @return int
     */
    public function getPositionId(): int
    {
        return $this->position_id;
    }

    /**
     * @param int $position_id
     */
    public function setPositionId(int $position_id): void
    {
        $this->position_id = $position_id;
    }

    /**
     * @return float|null
     */
    public function getSalary(): ?float
    {
        return $this->salary;
    }

    /**
     * @param float|null $salary
     */
    public function setSalary(?float $salary): void
    {
        $this->salary = $salary;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @param int $role_id
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }


}