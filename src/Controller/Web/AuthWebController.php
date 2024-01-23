<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\extends\WorkerDataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\Worker\WorkerAuthService;

class AuthWebController extends WebController
{
    protected AuthService $workerAuthService;
    public function __construct(
        array $url,
        AuthService $workerAuthService = null,
    )
    {
        parent::__construct($url);
        $this->workerAuthService = $workerAuthService ?? new WorkerAuthService(
            new WorkerDataMapper(new WorkerDataSource(MySql::getInstance()))
        );
    }
    /**
     * @return void
     *
     * url = /web/auth/user
     */
    public function user() {
        if(isset($this->url[3])) {
            /**
             * url = /web/auth/user/registration
             */
            if($this->url[3] === 'registration') {
                $data = [
                    'title' => 'Registration'
                ];
                $this->view(VIEW_FRONTEND . 'pages/user/forms/registration', $data);
            }

            /**
             * url = /web/auth/user/login
             */
            if($this->url[3] === 'login') {
                $data = [
                    'title' => 'Login'
                ];
                $this->view(VIEW_FRONTEND . 'pages/user/forms/login', $data);
            }

            /**
             * url = /web/auth/user/logout
             */
            if($this->url[3] === 'logout') {
                if(SessionHelper::removeUserSession()) {
                    $data = [
                        'title' => 'Homepage'
                    ];
                    $this->view(VIEW_FRONTEND . 'index', $data);
                } else {
                    $this->error(
                        'Logout Error',
                        'You can not log out from the <b>User</b> account because you are not logged in!'
                    );
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/auth/worker
     */
    public function worker() {
        if (isset($this->url[3])) {
            /**
             * url = /web/auth/worker/login
             */
            if ($this->url[3] === 'login') {
                $data = [
                    'title' => 'Login | Worker'
                ];
                $this->view(VIEW_FRONTEND . 'pages/worker/forms/login', $data);
            }

            /**
             * url = /web/auth/worker/recovery-password
             */
            if ($this->url[3] === 'recovery-password') {
                $isValidRequest = $this->workerAuthService->recoveryWorkerPassword();
                if(isset($isValidRequest['error'])) {
                    $this->error(
                        $isValidRequest['error']['title'],
                        $isValidRequest['error']['message']
                    );
                } else {
                    $data = [
                        'title' => 'Change Password'
                    ];
                    SessionHelper::setRecoveryCodeSession($isValidRequest['recovery_code']);
                    $this->view(VIEW_FRONTEND . 'pages/worker/forms/change_password', $data);
                }
            }

            /**
             * url = /web/auth/worker/logout
             */
            if ($this->url[3] === 'logout') {
                if(SessionHelper::removeWorkerSession()) {
                    $data = [
                        'title' => 'Homepage'
                    ];
                    $this->view(VIEW_FRONTEND . 'index', $data);
                } else {
                    $this->error(
                        'Logout Error',
                        'You can not log out from the <b>Worker</b> account because you are not logged in!'
                    );
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/auth/admin/
     */
    public function admin()
    {
        if (isset($this->url[3])) {
            /**
             * url = /web/auth/admin/login
             */
            if ($this->url[3] === 'login') {
                $data = [
                    'title' => 'Login | Admin'
                ];
                $this->view(VIEW_FRONTEND . 'pages/admin/forms/login', $data);
            }

            /**
             * url = /web/auth/admin/logout
             */
            if ($this->url[3] === 'logout') {
                if(SessionHelper::removeAdminSession()) {
                    $data = [
                        'title' => 'Homepage'
                    ];
                    $this->view(VIEW_FRONTEND . 'index', $data);
                } else {
                    $this->error(
                        'Logout Error',
                        'You can not log out from the <b>Admin</b> account because you are not logged in!'
                    );
                }
            }
        }
    }
}