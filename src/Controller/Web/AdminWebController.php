<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Helper\Data\AdminDefault;
use Src\Helper\Provider\Api\Web\WebApiProvider;
use Src\Helper\Redirector\impl\UrlRedirector;
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

    public function checkPermission(array $url = []): void
    {
        if(!SessionHelper::getAdminSession()
            && $url[0] !== AdminDefault::getRegistrationUrl()) {
            /**
             * Redirect to the Admin login page
             */
            SessionHelper::setRememberUrlSession($url);
            UrlRedirector::redirect(WebApiProvider::adminLogin());
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

            $this->view(
                ADMIN_PAGES['change_default']['path'],
                ADMIN_PAGES['change_default']['data']
            );
        } else {
            $this->error('Error', $result['error']);
        }
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
     * url = /web/admin/profile/...
     *         0    1       2
     */
    public function profile()
    {
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
            else if ($menuItemName === 'workers') {
               $this->_workers();
            }

            /**
             * url = /web/admin/profile/services
             */
            else if ($menuItemName === 'services') {
                $this->_services();
            }

            /**
             * url = /web/admin/profile/departments
             */
            else if($menuItemName === 'departments') {
                $this->_departments();
            }

            /**
             * url = /web/admin/profile/positions
             */
            else if($menuItemName === 'positions') {
                $this->_positions();
            }

            else if($menuItemName === 'affiliates') {
                $this->_affiliates();
            }

            else {
                $this->error();
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
        $this->view(
            ADMIN_PAGES['home']['path'],
            ADMIN_PAGES['home']['data']
        );
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/workers
     */
    protected function _workers() {
        $this->view(
            ADMIN_PAGES['workers']['path'],
            ADMIN_PAGES['workers']['data']
        );
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/services
     */
    protected function _services() {
        $this->view(
            ADMIN_PAGES['services']['path'],
            ADMIN_PAGES['services']['data'],
        );
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/departments
     */
    protected function _departments() {
        $this->view(
            ADMIN_PAGES['departments']['path'],
            ADMIN_PAGES['departments']['data'],
        );
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/positions
     */
    public function _positions() {
        $this->view(
            ADMIN_PAGES['positions']['path'],
            ADMIN_PAGES['positions']['data'],
        );
    }

    /**
     * @return void
     *
     * url = /web/admin/profile/affiliates
     */
    public function _affiliates() {
        $this->view(
            ADMIN_PAGES['affiliates']['path'],
            ADMIN_PAGES['affiliates']['data']
        );
    }

}