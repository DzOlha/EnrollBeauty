<?php

namespace Src\Model\Table;

class WorkersServicePricing
{
    public static string $table = 'workers_service_pricing';
    public static string $id = "workers_service_pricing.id";
    public static string $service_id = 'workers_service_pricing.service_id';
    public static string $worker_id = 'workers_service_pricing.worker_id';
    public static string $price = 'workers_service_pricing.price';
    public static string $currency = 'workers_service_pricing.currency';
}