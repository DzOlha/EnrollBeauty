<?php

namespace Src\Model\Table;

class WorkersServiceSchedule
{
    public static string $table = 'workers_service_schedule';
    public static string $id = "workers_service_schedule.id";
    public static string $worker_id = 'workers_service_schedule.worker_id';
    public static string $affiliate_id = 'workers_service_schedule.affiliate_id';
    public static string $service_id = 'workers_service_schedule.service_id';
    public static string $day = 'workers_service_schedule.day';
    public static string $start_time = 'workers_service_schedule.start_time';
    public static string $end_time = 'workers_service_schedule.end_time';
    public static string $order_id = 'workers_service_schedule.order_id';

}