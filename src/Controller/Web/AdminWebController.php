<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\AdminDataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Service\Auth\Admin\AdminAuthService;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;

class AdminWebController extends WebController
{
    protected AuthService $authService;

    /**
     * @param ?AuthService $authService
     */
    public function __construct(array $url, AuthService $authService = null)
    {
        parent::__construct($url);
        $this->authService = $authService ?? new AdminAuthService(
            $this->dataMapper, new UserAuthService(
                new UserDataMapper(new UserDataSource(MySql::getInstance()))
            )
        );
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new AdminDataMapper(new AdminDataSource(MySql::getInstance()));
    }

    /**
     * @return void
     *
     * url = /web/admin/auth/
     */
    public function auth()
    {
        if (isset($this->url[3])) {
            /**
             * url = /web/admin/auth/login
             */
            if ($this->url[3] === 'login') {
                $this->_login();
            }

            /**
             * url = /web/admin/auth/logout
             */
            if ($this->url[3] === 'logout') {
                $this->_logout();
            }
        }
    }


    /**
     * @return void
     *
     * url = /{the admin registration url is here}
     */
    public function adminDefaultRegistration()
    {
        $result = $this->authService->defaultAdminRegister();
        /**
         * If registration of the default admin has been successfully done right now
         * OR
         * we try to change admin default data and the account has been registered
         * before (we just close the change form then and not completed the process)
         */
        if (isset($result['success']) ||
            (isset($result['default_already_registered']) && $result['default_already_registered'])
        ) {
            $data = [
                'title' => 'Change Admin Info'
            ];
            $this->view(VIEW_FRONTEND . 'pages/admin/forms/change_default', $data);
        } else {
            $this->error('Error', $result['error']);
        }
    }

    /**
     * @return void
     *
     * url = /web/admin/auth/login
     */
    private function _login()
    {
        $data = [
            'title' => 'Login | Admin'
        ];
        $this->view(VIEW_FRONTEND . 'pages/admin/forms/login', $data);
    }

    private function _accessDenied()
    {
        $this->error(
            'Access Denied!',
            'The requested page not found! Please, log in as an Admin to visit your account!'
        );
    }

    /**
     * @return void
     *
     * url = /web/admin/auth/logout
     */
    protected function _logout()
    {
        SessionHelper::removeAdminSession();
        $data = [
            'title' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'index', $data);
    }


    /**
     * @return void
     *
     * url = /web/admin/profile/...
     *         0    1       2
     */
    public function profile()
    {
        $session = SessionHelper::getAdminSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                $menuItemName = $this->url[3];
                /**
                 * url = /web/admin/profile/home
                 */
                if ($menuItemName === 'home') {
                    $this->_home();
                }
                /**
                 * url = /web/admin/profile/workers
                 */
                if ($menuItemName === 'workers') {
                   $this->_workers();
                }
                /**
                 * url = /web/admin/profile/services
                 */
                if ($menuItemName === 'services') {
                    $this->_services();
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/home
     */
    protected function _home()
    {
        $data = [
            'title'     => 'Admin Account',
            'page_name' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'pages/admin/profile/home', $data);
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/workers
     */
    protected function _workers() {
        $data = [
            'title'     => 'User Management',
            'page_name' => 'Workers'
        ];
        $this->view(VIEW_FRONTEND . 'pages/admin/profile/workers', $data);
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/services
     */
    protected function _services() {
        $data = [
            'title'     => 'Service Management',
            'page_name' => 'Services'
        ];
        $this->view(VIEW_FRONTEND . 'pages/admin/profile/services', $data);
    }
}