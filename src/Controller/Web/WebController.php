<?php

namespace Controller\Web;

use Controller\AbstractController;
use DB\Database\MySql;
use Model\DataMapper\DataMapper;
use Model\DataMapper\extends\MainDataMapper;
use Model\DataSource\extends\MainDataSource;

class WebController extends AbstractController
{
    public function getTypeDataMapper(): DataMapper {
        return new MainDataMapper(new MainDataSource(MySql::getInstance()));
    }
    /**
     * Load the view (checks for the file).
     */
    public function view($view, $data = [], $arr = []): void
    {
        if (file_exists($view . '.php')) {
            require_once $view . '.php';
        } else {
            exit('View does not exists!');
        }
    }

    public function index() {
        $data = [
            'title' => 'Hello Page'
        ];
        $this->view(VIEW_FRONTEND, 'hello', $data);
    }
}