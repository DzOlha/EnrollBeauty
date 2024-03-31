<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\MainDataMapper;
use Src\Model\DataSource\extends\MainDataSource;

class UserWebController extends WebController
{
    public function __construct(array $url)
    {
        parent::__construct($url);
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new MainDataMapper(new MainDataSource(MySql::getInstance()));
    }

    public function checkPermission(): void
    {
        if(!SessionHelper::getUserSession()) {
            $this->_accessDenied();
        }
    }

    /**
     * @return void
     *
     * url = /web/user/profile/home
     */
    protected function _home()
    {
        $this->view(
            USER_PAGES['home']['path'],
            USER_PAGES['home']['data']
        );
    }

    private function _accessDenied() {
        $this->error(
            'Access Denied!',
            'The requested page not found! Please, log in as an User to visit your account!'
        );
    }

    /**
     * @return void
     *
     * url = /web/user/profile/...
     *         0    1       2
     */
    public function profile()
    {
//        if(!isset($this->url[3]) && isset($_GET['user_id']) && $_GET['user_id'] !== '') {
//            if(SessionHelper::getAdminSession() || SessionHelper::getWorkerSession()) {
//                $data = [
//                    'title' => 'User Profile',
//                    'page_name' => 'User Profile'
//                ];
//                $this->view(VIEW_FRONTEND . 'pages/user/profile/profile', $data);
//            }
//        }

        if (isset($this->url[3])) {
            $menuItemName = $this->url[3];

            /**
             * url = /web/user/profile/home
             */
            if ($menuItemName === 'home') {
                $this->_home();
            }
            /**
             * url = /web/user/profile/settings
             */
            else if($menuItemName === 'settings') {
                $this->_settings();
            }
            else {
                $this->error();
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/user/profile/settings
     */
    private function _settings() {
        $this->view(
            USER_PAGES['settings']['path'],
            USER_PAGES['settings']['data']
        );
    }
}