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
        $this->view(
            OPEN_PAGES['index']['path'],
            OPEN_PAGES['index']['data']
        );
    }

    public function error($title = null, $message = null) {
        $data = null;
        if($title && $message) {
            $data = [
                'title' => $title,
                'message' => $message
            ];
        }
        $this->view(
            OPEN_PAGES['error']['path'],
            $data ?? OPEN_PAGES['error']['data'],
        );
        exit();
    }
}