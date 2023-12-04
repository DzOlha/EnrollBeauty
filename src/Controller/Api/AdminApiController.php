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
use Src\Service\Auth\Worker\WorkerAuthService;

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
     * url = /api/admin/changeDefault
     */
    public function changeDefault()
    {
        $this->returnJson(
            $this->authService->changeDefaultAdminData()
        );
    }

    public function login()
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
     * url = /api/admin/getAdminInfo
     */
    public function getAdminInfo(): void
    {
        if (!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
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
     * url = /api/admin/workers
     */
    public function getWorkers(): void
    {
        if (!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
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
        $this->returnJsonSuccess(true, $result);
    }

    /**
     * @return void
     *
     * url = /api/admin/getAllPositionsRoles
     */
    public function getAllPositionsRoles(): void
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

        /**
         * Get roles
         */
        $roles = $this->dataMapper->selectAllRoles();
        if($positions === false) {
            $this->returnJson(['error' => "An error occurred while getting all roles"]);
        }
        if($positions === null) {
            $this->returnJson(['error' => "There are no roles found!"]);
        }

        $this->returnJson([
            'success' => true,
            'data' => [
                'positions' => $positions,
                'roles' => $roles
            ]
        ]);
    }

    /**
     * @return void
     *
     *  * url = /api/admin/registerWorker
     */
    public function registerWorker(): void
    {
        $this->returnJson(
            $this->authService->registerWorker()
        );
    }
}