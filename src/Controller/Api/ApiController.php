<?php

namespace Src\Controller\Api;

use Src\Controller\AbstractController;
use Src\DB\Database\MySql;
use JetBrains\PhpStorm\NoReturn;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\MainDataMapper;
use Src\Model\DataSource\extends\MainDataSource;

class ApiController extends AbstractController
{
    public function __construct()
    {
        $this->dataMapper = $this->dataMapper();
    }

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