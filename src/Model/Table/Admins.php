<?php

namespace Src\Model\Table;

class Admins
{
    public static string $table = 'admins';
    public static string $id = "admins.id";
    public static string $name = 'admins.name';
    public static string $surname = 'admins.surname';
    public static string $password = 'admins.password';
    public static string $email = 'admins.email';
    public static string $created_date = 'admins.created_date';
    public static string $referral_parent_id = 'admins.referral_parent_id';
    public static string $last_login_date = 'admins.last_login_date';
    public static string $role_id = 'admins.role_id';
    public static string $status = 'admins.status';
}