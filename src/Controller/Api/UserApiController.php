<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;

class UserApiController extends ApiController
{
    protected AuthService $authService;

    /**
     * @param ?AuthService $authService
     */
    public function __construct(AuthService $authService = null)
    {
        parent::__construct();
        $this->authService = $authService ?? new UserAuthService($this->dataMapper);
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new UserDataMapper(new UserDataSource(MySql::getInstance()));
    }

    /**
     * @return void
     *
     *       type    controller      method
     * url:  /api       /user        /register
     */
    public function register() {

    }
}