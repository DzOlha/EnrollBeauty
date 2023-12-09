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

    /**
     * @return void
     *
     * url = /web/user/auth
     */
    public function auth() {
        if(isset($this->url[3])) {
            /**
             * url = /web/user/auth/registration
             */
            if($this->url[3] === 'registration') {
                $this->_registration();
            }

            /**
             * url = /web/user/auth/login
             */
            if($this->url[3] === 'login') {
                $this->_login();
            }

            /**
             * url = /web/user/auth/logout
             */
            if($this->url[3] === 'logout') {
                $this->_logout();
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/user/auth/login
     */
    protected function _login()
    {
        $data = [
            'title' => 'Login'
        ];
        $this->view(VIEW_FRONTEND . 'pages/user/forms/login', $data);
    }

    /**
     * @return void
     *
     * url = /web/user/auth/registration
     */
    protected function _registration()
    {
        $data = [
            'title' => 'Registration'
        ];
        $this->view(VIEW_FRONTEND . 'pages/user/forms/registration', $data);
    }

    /**
     * @return void
     *
     * url = /web/user/auth/logout
     */
    protected function _logout()
    {
        SessionHelper::removeUserSession();
        $data = [
            'title' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'index', $data);
    }

    /**
     * @return void
     *
     * url = /web/user/profile/home
     */
    protected function _home()
    {
        $data = [
            'title' => 'Account',
            'page_name' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'pages/user/profile/home', $data);
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
        if(!isset($this->url[3]) && isset($_GET['user_id']) && $_GET['user_id'] !== '') {
            if(SessionHelper::getAdminSession() || SessionHelper::getWorkerSession()) {
                $data = [
                    'title' => 'User Profile',
                    'page_name' => 'User Profile'
                ];
                $this->view(VIEW_FRONTEND . 'pages/user/profile/profile', $data);
            }
        }

        $session = SessionHelper::getUserSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                $menuItemName = $this->url[3];

                /**
                 * url = /web/user/profile/home
                 */
                if ($menuItemName === 'home') {
                    $this->_home();
                }
            }
        }
    }
}