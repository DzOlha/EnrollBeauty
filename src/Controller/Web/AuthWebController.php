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
                $this->view(
                    USER_PAGES['registration']['path'],
                    USER_PAGES['registration']['data']
                );
            }

            /**
             * url = /web/auth/user/login
             */
            else if($this->url[3] === 'login') {
                $this->view(
                    USER_PAGES['login']['path'],
                    USER_PAGES['login']['data']
                );
            }

            /**
             * url = /web/auth/user/logout
             */
            else if($this->url[3] === 'logout') {
                if(SessionHelper::removeUserSession()) {
                    $this->view(
                        COMMON_PAGES['index']['path'],
                        COMMON_PAGES['index']['data']
                    );
                } else {
                    $this->error(
                        'Logout Error',
                        'You can not log out from the <b>User</b> account because you are not logged in!'
                    );
                }
            }
            else {
                $this->error();
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
                $this->view(
                    WORKER_PAGES['login']['path'],
                    WORKER_PAGES['login']['data']
                );
            }

            /**
             * url = /web/auth/worker/recovery-password
             */
            else if ($this->url[3] === 'recovery-password') {
                $isValidRequest = $this->workerAuthService->recoveryWorkerPassword();
                if(isset($isValidRequest['error'])) {
                    $this->error(
                        $isValidRequest['error']['title'],
                        $isValidRequest['error']['message']
                    );
                } else {
                    SessionHelper::setRecoveryCodeSession($isValidRequest['recovery_code']);
                    $this->view(
                        WORKER_PAGES['change_password']['path'],
                        WORKER_PAGES['change_password']['data'],
                    );
                }
            }

            /**
             * url = /web/auth/worker/logout
             */
            else if ($this->url[3] === 'logout') {
                if(SessionHelper::removeWorkerSession()) {
                    $this->view(
                        COMMON_PAGES['index']['path'],
                        COMMON_PAGES['index']['data'],
                    );
                } else {
                    $this->error(
                        'Logout Error',
                        'You can not log out from the <b>Worker</b> account because you are not logged in!'
                    );
                }
            }
            else {
                $this->error();
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
                $this->view(
                    ADMIN_PAGES['login']['path'],
                    ADMIN_PAGES['login']['data']
                );
            }

            /**
             * url = /web/auth/admin/logout
             */
            else if ($this->url[3] === 'logout') {
                if(SessionHelper::removeAdminSession()) {
                    $this->view(
                        COMMON_PAGES['index']['path'],
                        COMMON_PAGES['index']['data'],
                    );
                } else {
                    $this->error(
                        'Logout Error',
                        'You can not log out from the <b>Admin</b> account because you are not logged in!'
                    );
                }
            }
            else {
                $this->error();
            }
        }
    }
}