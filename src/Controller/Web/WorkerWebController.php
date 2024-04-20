<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Helper\Provider\Api\Web\WebApiProvider;
use Src\Helper\Redirector\impl\UrlRedirector;
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

    public function checkPermission(array $url = []): void
    {
        if(!SessionHelper::getWorkerSession()) {
            /**
             * Redirect to the Worker login page
             */
            SessionHelper::setRememberUrlSession($url);
            UrlRedirector::redirect(WebApiProvider::workerLogin());
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

            /**
             * url = /web/worker/profile/home
             */
            if ($menuItemName === 'home') {
                $this->_home();
            }

            /**
             * url = /web/worker/profile/schedule
             */
            else if ($menuItemName === 'schedule') {
                $this->_schedule();
            }

            /**
             * url = /web/worker/profile/services
             */
            else if ($menuItemName === 'services') {
                $this->_services();
            }

            /**
             * url = /web/worker/profile/pricing
             */
            else if($menuItemName === 'pricing') {
                $this->_pricing();
            }

            /**
             * url = /web/worker/profile/settings
             */
            else if($menuItemName === 'settings') {
                $this->_settings();
            }

            /**
             * url = /web/worker/profile/orders
             */
            else if($menuItemName === 'orders') {
                $this->_orders();
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
        $this->view(
            WORKER_PAGES['home']['path'],
            WORKER_PAGES['home']['data']
        );
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/schedule
     */
    private function _schedule() {
        $this->view(
            WORKER_PAGES['schedule']['path'],
            WORKER_PAGES['schedule']['data']
        );
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/services
     */
    private function _services() {
        $this->view(
            WORKER_PAGES['services']['path'],
            WORKER_PAGES['services']['data'],
        );
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/pricing
     */
    private function _pricing() {
        $this->view(
            WORKER_PAGES['pricing']['path'],
            WORKER_PAGES['pricing']['data']
        );
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/settings
     */
    private function _settings() {
        $this->view(
            WORKER_PAGES['settings']['path'],
            WORKER_PAGES['settings']['data']
        );
    }

    /**
     * @return void
     *
     * url = /web/worker/profile/orders
     */
    private function _orders() {
        $this->view(
            WORKER_PAGES['orders']['path'],
            WORKER_PAGES['orders']['data']
        );
    }
}