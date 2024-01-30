<?php

namespace Src\Model\Table;

class OrdersService
{
    public static string $table = 'orders_service';
    public static string $id = "orders_service.id";
    public static string $schedule_id = "orders_service.schedule_id";
    public static string $price_id = "orders_service.price_id";
    public static string $user_id = 'orders_service.user_id';
    public static string $email = 'orders_service.email';
    public static string $affiliate_id = 'orders_service.affiliate_id';
    public static string $start_datetime = 'orders_service.start_datetime';
    public static string $end_datetime = 'orders_service.end_datetime';
    public static string $completed_datetime = 'orders_service.completed_datetime';
    public static string $canceled_datetime = 'orders_service.canceled_datetime';
    public static string $created_datetime = 'orders_service.created_datetime';
}