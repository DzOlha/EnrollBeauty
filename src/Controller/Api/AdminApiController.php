<?php

namespace Src\Controller\Api;

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

class AdminApiController extends ApiController
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
     * url = /api/admin/auth/
     */
    public function auth() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/auth/change-default-admin-info
             */
            if($this->url[3] === 'change-default-admin-info') {
                $this->_changeDefault();
            }

            /**
             * url = /api/admin/auth/login
             */
            if($this->url[3] === 'login') {
                $this->_login();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/profile/
     */
    public function profile() {
        if (!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/profile/get
             */
            if($this->url[3] === 'get') {
                $this->_getAdminInfo();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/
     */
    public function worker() {
        if (!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/worker/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/worker/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getWorkers();
                    }
                }
            }

            /**
             * url = /api/admin/worker/register
             */
            if($this->url[3] === 'register') {
                $this->_registerWorker();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/
     */
    public function position() {
        if (!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/position/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/position/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getAllPositions();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/role/
     */
    public function role() {
        if (!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/role/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/role/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getAllRoles();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/auth/change-default-admin-info
     */
    protected function _changeDefault()
    {
        $this->returnJson(
            $this->authService->changeDefaultAdminData()
        );
    }

    /**
     * @return void
     *
     * url = /api/admin/auth/login
     */
    protected function _login()
    {
        $this->returnJson(
            $this->authService->loginAdmin()
        );
    }

    private function _getAdminId()
    {
        $userId = 0;
        if (isset($_GET['admin_id']) && $_GET['admin_id'] !== '') {
            $userId = htmlspecialchars(trim($_GET['admin_id']));
        } else {
            $sessionUserId = SessionHelper::getAdminSession();
            if ($sessionUserId) {
                $userId = $sessionUserId;
            }
        }
        return $userId;
    }

    /**
     * @return void
     *
     * url = /api/admin/profile/get
     */
    protected function _getAdminInfo(): void
    {
        $adminId = $this->_getAdminId();
        /**
         *  [
         *      'name' =>
         *      'surname' =>
         *      'email' =>
         * ]
         */
        $result = $this->dataMapper->selectAdminInfoById($adminId);

        if ($result) {
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting admin's info"
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/get/all
     */
    protected function _getWorkers(): void
    {
        $param = $this->_getLimitPageFieldOrderOffset();
        /**
         *  * [
         *      0 => [
         *          id =>
         *          name =>
         *          surname =>
         *          email =>
         *          position =>
         *          salary =>
         *          experience =>
         *      ]
         *      ....................
         * ]
         */
        $result = $this->dataMapper->selectAllWorkersForAdmin(
            $param['limit'],
            $param['offset'],
            $param['order_field'],
            $param['order_direction']
        );
        if($result === false) {
            $this->returnJsonError(
                "The error occurred while getting data about all workers!"
            );
        }
        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * @return void
     *
     * url = /api/admin/position/get/all
     */
    protected function _getAllPositions(): void
    {
        /**
         * Get positions
         */
        $positions = $this->dataMapper->selectAllPositions();
        if($positions === false) {
            $this->returnJson(['error' => "An error occurred while getting all positions"]);
        }
        if($positions === null) {
            $this->returnJson(['error' => "There are no positions found!"]);
        }

        $this->returnJson([
            'success' => true,
            'data' => $positions
        ]);
    }

    /**
     * @return void
     *
     * url = /api/admin/role/get/all
     */
    protected function _getAllRoles(): void
    {
        /**
         * Get roles
         */
        $roles = $this->dataMapper->selectAllRoles();
        if($roles === false) {
            $this->returnJson(['error' => "An error occurred while getting all roles"]);
        }
        if($roles === null) {
            $this->returnJson(['error' => "There are no roles found!"]);
        }

        $this->returnJson([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/register
     */
    protected function _registerWorker(): void
    {
        $this->returnJson(
            $this->authService->registerWorker()
        );
    }
}