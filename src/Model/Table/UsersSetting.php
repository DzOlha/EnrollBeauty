<?php

namespace Src\Model\Table;

class UsersSetting
{
    public static string $table = 'users_setting';
    public static string $id = "users_setting.id";
    public static string $user_id = 'users_setting.user_id';
    public static string $recovery_code = 'users_setting.recovery_code';
    public static string $date_of_sending = 'users_setting.date_of_sending';

}