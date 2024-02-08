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

    public function checkPermission(): void
    {
        if(!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
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
     * url = /web/worker/profile/...
     *         0    1       2
     */
    public function profile()
    {
        if (isset($this->url[3])) {
            $menuItemName = $this->url[3];

            if ($menuItemName === 'home') {
                $this->_home();
            }

            else if ($menuItemName === 'schedule') {
                $this->_schedule();
            }

            else if ($menuItemName === 'services') {
                $this->_services();
            }

            else if($menuItemName === 'pricing') {
                $this->_pricing();
            }
            else {
                $this->error();
            }
        }
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/home
     */
    public function _home()
    {
        $data = [
            'title' => 'Worker Account',
            'page_name' => 'Homepage'
        ];
        $this->view(VIEW_FRONTEND . 'pages/worker/profile/home', $data);
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
     * url = /web/worker/profile/services
     */
    private function _services() {
        $data = [
            'title' => 'Service Management',
            'page_name' => 'Services'
        ];
        $this->view(VIEW_FRONTEND . 'pages/worker/profile/services', $data);
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