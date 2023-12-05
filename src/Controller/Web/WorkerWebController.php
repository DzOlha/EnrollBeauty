<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\extends\WorkerDataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\Worker\WorkerAuthService;

class WorkerWebController extends WebController
{
    protected AuthService $authService;

    /**
     * @param ?AuthService $authService
     */
    public function __construct(array $url, AuthService $authService = null)
    {
        parent::__construct($url);
        $this->authService = $authService ?? new WorkerAuthService($this->dataMapper);
    }

    public function getTypeDataMapper(): WorkerDataMapper
    {
        return new WorkerDataMapper(new WorkerDataSource(MySql::getInstance()));
    }

    /**
     * @return void
     *
     * url = /web/worker/recoveryPassword
     */
    public function recoveryPassword()
    {
        $isValidRequest = $this->authService->recoveryWorkerPassword();
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
     * @return void
     *
     * url = /web/worker/login
     */
    public function login() {
        $data = [
            'title' => 'Login | Worker'
        ];
        $this->view(VIEW_FRONTEND . 'pages/worker/forms/login', $data);
    }

    private function _accessDenied() {
        $this->error(
            'Access Denied!',
            'The requested page not found! Please, log in as an Worker to visit your account!'
        );
    }

    /**
     * @return void
     *
     * url = /web/worker/account
     */
    public function account()
    {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            $data = [
                'title' => 'Worker Account',
                'page_name' => 'Homepage'
            ];
            $this->view(VIEW_FRONTEND . 'pages/worker/profile/account', $data);
        }
    }

    /**
     * @return void
     *
     * url = /web/worker/logout
     */
    public function logout()
    {
        SessionHelper::removeWorkerSession();
        $data = [
            'title' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'index', $data);
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/...
     *         0    1       2
     */
    public function profile()
    {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                $menuItemName = $this->url[3];
                if ($menuItemName === 'settings') {

                }
                if ($menuItemName === 'schedule') {
                    $this->_schedule();
                }

                if($menuItemName === 'pricing') {
                    $this->_pricing();
                }
               
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/schedule
     */
    private function _schedule() {
        $data = [
            'title' => 'Schedule Management',
            'page_name' => 'Schedule'
        ];
        $this->view(VIEW_FRONTEND . 'pages/worker/profile/schedule', $data);
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/pricing
     */
    private function _pricing() {
        $data = [
            'title' => 'Service Pricing',
            'page_name' => 'Price-list'
        ];
        $this->view(VIEW_FRONTEND . 'pages/worker/profile/pricing', $data);
    }


}