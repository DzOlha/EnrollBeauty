<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\extends\WorkerDataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\Worker\WorkerAuthService;

class WorkerApiController extends ApiController
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
     * url = /api/worker/changePassword
     */
    public function changePassword() {
        $changed =  $this->authService->changeWorkerPassword();
        if(isset($changed['success'])) {
            SessionHelper::removeRecoveryCodeSession();
        }
       $this->returnJson($changed);
    }

    /**
     * @return void
     *
     * url = /api/worker/login
     */
    public function login() {
        $this->returnJson(
            $this->authService->loginWorker()
        );
    }
}