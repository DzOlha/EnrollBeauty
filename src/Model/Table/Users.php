<?php

namespace Src\Model\Table;

class Users
{
    public static string $table = 'users';
    public static string $id = "users.id";
    public static string $name = 'users.name';
    public static string $surname = 'users.surname';
    public static string $password = 'users.password';
    public static string $email = 'users.email';
    public static string $created_date = 'users.created_date';
    public static string $last_login_date = 'users.last_login_date';
    public static string $role_id = 'users.role_id';
}