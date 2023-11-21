<?php

namespace Src\Controller\Web;

use Src\DB\Database\MySql;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\AdminDataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Service\Auth\Admin\AdminAuthService;
use Src\Service\Auth\AuthService;

class AdminWebController extends WebController
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
     * url = /{the admin registration url is here}
     */
    public function adminDefaultRegistration() {
        $result = $this->authService->defaultAdminRegister();
        /**
         * If registration of the default admin has been successfully done right now
         * OR
         * we try to change admin default data and the account has been registered
         * before (we just close the change form then and not completed the process)
         */
        if( isset($result['success']) ||
            (isset($result['default_already_registered']) && $result['default_already_registered'])
        ) {
            $data = [
                'title' => 'Change Admin Info'
            ];
            $this->view(VIEW_FRONTEND . 'pages/admin/forms/change_default', $data);
        } else {
            $this->error('Error', $result['error']);
        }
    }

    /**
     *
     * url = /web/admin/login
     */
    public function login() {
        $data = [
            'title' => 'Login'
        ];
        $this->view(VIEW_FRONTEND . 'pages/admin/forms/login', $data);
    }
}