<?php

namespace Src\Controller\Web;

use Src\Controller\AbstractController;
use Src\DB\Database\MySql;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\MainDataMapper;
use Src\Model\DataSource\extends\MainDataSource;

class WebController extends AbstractController
{
    public function __construct(array $url)
    {
        parent::__construct($url);
    }

    public function getTypeDataMapper(): DataMapper {
        return new MainDataMapper(new MainDataSource(MySql::getInstance()));
    }
    /**
     * Load the view (checks for the file).
     */
    public function view($view, $data = [], $arr = []): bool
    {
        $viewPath = $view.'.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
            return true;
        } else {
            return false;
        }
    }

    public function index() {
        $data = [
            'title' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND.'index', $data);
    }

    public function error($title = null, $message = null) {
        $data = [
            'title' => $title ?? 'Page Not Found',
            'message' => $message ?? 'The requested page not found!'
        ];
        $this->view(VIEW_FRONTEND . 'pages/system/error', $data);
    }
}