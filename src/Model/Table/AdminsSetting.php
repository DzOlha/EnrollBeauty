<?php

namespace Src\Model\Table;

class AdminsSetting
{
    public static string $table = 'admins_setting';
    public static string $id = "admins_setting.id";
    public static string $admin_id = 'admins_setting.admin_id';
    public static string $recovery_code = 'admins_setting.recovery_code';
    public static string $activation_code = 'admins_setting.activation_code';
    public static string $date_of_sending = 'admins_setting.date_of_sending';
}