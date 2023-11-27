<?php

namespace Src\Model\Table;

class WorkersSetting
{
    public static string $table = 'workers_setting';
    public static string $id = "workers_setting.id";
    public static string $worker_id = 'workers_setting.worker_id';
    public static string $recovery_code = 'workers_setting.recovery_code';
    public static string $activation_code = 'workers_setting.activation_code';
    public static string $date_of_sending = 'workers_setting.date_of_sending';
}