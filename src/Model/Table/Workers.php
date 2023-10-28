<?php

namespace Src\Model\Table;

class Workers
{
    public static string $table = 'workers';
    public static string $id = "workers.id";
    public static string $name = 'workers.name';
    public static string $surname = 'workers.surname';
    public static string $password = 'workers.password';
    public static string $email = 'workers.email';
    public static string $gender = 'workers.gender';
    public static string $age = 'workers.age';
    public static string $years_of_experience = 'workers.years_of_experience';
    public static string $position_id = 'workers.position_id';
    public static string $salary = 'workers.salary';
    public static string $hours_working = 'workers.hours_working';
    public static string $role_id = 'workers.role_id';
}