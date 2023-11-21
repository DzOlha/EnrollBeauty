<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\AdminDataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Service\Auth\Admin\AdminAuthService;
use Src\Service\Auth\AuthService;

class AdminApiController extends ApiController
{
    protected AuthService $authService;

    /**
     * @param ?AuthService $authService
     */
    public function __construct(AuthService $authService = null)
    {
        parent::__construct();
        $this->authService = $authService ?? new AdminAuthService($this->dataMapper);
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
    public function changeDefault() {
        $this->returnJson(
            $this->authService->changeDefaultAdminData()
        );
    }
}