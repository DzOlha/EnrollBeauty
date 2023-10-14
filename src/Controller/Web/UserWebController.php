<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\MainDataMapper;
use Src\Model\DataSource\extends\MainDataSource;

class UserWebController extends WebController
{
    public function getTypeDataMapper(): DataMapper {
        return new MainDataMapper(new MainDataSource(MySql::getInstance()));
    }
    public function login() {
        $data = [
            'title' => 'Login'
        ];
        $this->view(VIEW_FRONTEND.'forms/login', $data);
    }
    public function registration() {
        $data = [
            'title' => 'Registration'
        ];
        $this->view(VIEW_FRONTEND.'forms/registration', $data);
    }
}