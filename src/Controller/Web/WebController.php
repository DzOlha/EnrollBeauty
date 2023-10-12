<?php

namespace Src\Controller\Web;

use Src\Controller\AbstractController;
use Src\DB\Database\MySql;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\MainDataMapper;
use Src\Model\DataSource\extends\MainDataSource;

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
        $viewPath = $view.'.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            exit('View does not exists!');
        }
    }

    public function index() {
        $data = [
            'title' => 'Hello Page'
        ];
        $this->view(VIEW_FRONTEND.'hello', $data);
    }
}