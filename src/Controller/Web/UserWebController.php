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
     * url = /web/user/login
     */
    public function login()
    {
        $data = [
            'title' => 'Login'
        ];
        $this->view(VIEW_FRONTEND . 'pages/user/forms/login', $data);
    }

    /**
     * @return void
     *
     * url = /web/user/registration
     */
    public function registration()
    {
        $data = [
            'title' => 'Registration'
        ];
        $this->view(VIEW_FRONTEND . 'pages/user/forms/registration', $data);
    }

    /**
     * @return void
     *
     * url = /web/user/account
     */
    public function account()
    {
        $session = SessionHelper::getUserSession();
        if (!$session) {
            $data = [
                'title' => 'Page Not Found',
                'message' => 'The requested page not found! Please, log in to visit your account!'
            ];
            $this->view(VIEW_FRONTEND . 'pages/system/error', $data);
        } else {
            $data = [
                'title' => 'Account',
                'page_name' => 'Homepage'
            ];
            $this->view(VIEW_FRONTEND . 'pages/user/profile/account', $data);
        }
    }

    /**
     * @return void
     *
     * url = /web/user/logout
     */
    public function logout()
    {
        SessionHelper::removeUserSession();
        $data = [
            'title' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'index', $data);
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
                if ($menuItemName === 'settings') {

                }
                if ($menuItemName === 'users') {

                }
                if ($menuItemName === 'workers') {
                    $data = [
                        'title' => 'User Management',
                        'page_name' => 'Workers'
                    ];
                    $this->view(VIEW_FRONTEND . 'pages/admin/profile/workers', $data);
                }
                if ($menuItemName === 'admins') {

                }
            }
        }
    }
}