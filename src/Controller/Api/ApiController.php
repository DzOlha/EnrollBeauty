<?php

namespace Controller\Api;

use Controller\AbstractController;
use DB\Database\MySql;
use JetBrains\PhpStorm\NoReturn;
use Model\DataMapper\DataMapper;
use Model\DataMapper\extends\MainDataMapper;
use Model\DataSource\extends\MainDataSource;

class ApiController extends AbstractController
{
    public function getTypeDataMapper(): DataMapper
    {
        return new MainDataMapper(new MainDataSource(MySql::getInstance()));
    }

    #[NoReturn] public function returnJson(array $array): void
    {
        echo json_encode($array);
        exit();
    }
}