<?php

namespace Src\Controller\Web;

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
}