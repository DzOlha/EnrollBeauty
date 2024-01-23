<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\extends\AdminDataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataMapper\extends\WorkerDataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Service\Auth\Admin\AdminAuthService;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;
use Src\Service\Auth\Worker\WorkerAuthService;

class AuthApiController extends ApiController
{
    protected AuthService $userAuthService;
    protected AuthService $workerAuthService;
    protected AuthService $adminAuthService;

    public function __construct(
        array       $url,
        AuthService $adminAuthService = null,
        AuthService $workerAuthService = null,
        AuthService $userAuthService = null
    )
    {
        parent::__construct($url);
        $this->userAuthService = $userAuthService ?? new UserAuthService(
            new UserDataMapper(new UserDataSource(MySql::getInstance()))
        );
        $this->workerAuthService = $workerAuthService ?? new WorkerAuthService(
            new WorkerDataMapper(new WorkerDataSource(MySql::getInstance()))
        );
        $this->adminAuthService = $adminAuthService ?? new AdminAuthService(
            new AdminDataMapper(new AdminDataSource(MySql::getInstance())),
            $this->userAuthService
        );
    }

    /**
     * @return void
     *
     * url = /api/auth/user/
     */
    public function user(): void
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/auth/user/register
             */
            if ($this->url[3] === 'register') {
                $this->returnJson(
                    $this->userAuthService->registerUser()
                );
            }

            /**
             * url = /api/auth/use/login
             */
            if ($this->url[3] === 'login') {
                $this->returnJson(
                    $this->userAuthService->loginUser()
                );
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/auth/worker/
     */
    public function worker(): void
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/auth/worker/login
             */
            if ($this->url[3] === 'login') {
                $this->returnJson(
                    $this->workerAuthService->loginWorker()
                );
            }

            /**
             * url = /api/auth/worker/change-password
             */
            if ($this->url[3] === 'change-password') {
                $changed = $this->workerAuthService->changeWorkerPassword();
                if (isset($changed['success'])) {
                    SessionHelper::removeRecoveryCodeSession();
                }
                $this->returnJson($changed);
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/auth/admin/
     */
    public function admin()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/auth/admin/change-default-admin-info
             */
            if ($this->url[3] === 'change-default-admin-info') {
                $this->returnJson(
                    $this->adminAuthService->changeDefaultAdminData()
                );
            }

            /**
             * url = /api/auth/admin/login
             */
            if ($this->url[3] === 'login') {
                $this->returnJson(
                    $this->adminAuthService->loginAdmin()
                );
            }
        }
    }
}