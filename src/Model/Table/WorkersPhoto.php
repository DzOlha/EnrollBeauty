<?php

namespace Src\Model\Table;

class WorkersPhoto
{
    public static string $table = 'workers_photo';
    public static string $id = "workers_photo.id";
    public static string $worker_id = 'workers_photo.worker_id';
    public static string $filename = 'workers_photo.filename';
    public static string $is_main = 'workers_photo.is_main';
}