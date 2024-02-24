<?php

namespace Src\Controller\Api;

use Src\Controller\AbstractController;
use Src\DB\Database\MySql;
use JetBrains\PhpStorm\NoReturn;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\MainDataMapper;
use Src\Model\DataSource\extends\MainDataSource;

class ApiController extends AbstractController
{
    public function __construct(array $url)
    {
        parent::__construct($url);
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new MainDataMapper(new MainDataSource(MySql::getInstance()));
    }

    #[NoReturn] public function returnJson(array $array): void
    {
        echo json_encode($array);
        exit();
    }
    protected function returnJsonError(string $message, int $code = null): void
    {
        if($code) {
            $this->returnJson([
                'error' => $message,
                'error_code' => $code
            ]);
        } else {
            $this->returnJson([
                'error' => $message
            ]);
        }
    }
    protected function returnJsonSuccess(string|bool $message, array $data = []) {
        $this->returnJson([
            'success' => $message,
            'data' => $data
        ]);
    }
    protected function _accessDenied(
        string $message = 'Access is denied to the requested resource!'
    ) {
        $this->returnJson([
            'error' => $message
        ]);
    }

    protected function _methodNotAllowed(array $allowedMethods)
    {
        $methods = implode(', ', $allowedMethods);
        $this->returnJson([
            'error' => "Method not allowed! Allowed ones: $methods"
        ]);
    }

    protected function _missingRequestFields()
    {
        $this->returnJson([
            'error' => "Missing request fields!"
        ]);
    }

    protected function _getLimitPageFieldOrderOffset(): array
    {
        /**
         * get limits of displaying rows
         */
        $limit = 10;
        if (isset($_GET['limit'])) {
            $limit = (int)htmlspecialchars(trim($_GET['limit']));
        }

        /**
         * get number of the pagination page that should be populated with the data
         */
        $page = 1;
        if (isset($_GET['page'])) {
            $page = (int)htmlspecialchars(trim($_GET['page']));
        }

        /**
         * get the name of the column (field) the data should be sorted by
         */
        $orderByField = 'id';
        if (isset($_GET['order_field'])) {
            $orderByField = htmlspecialchars(trim($_GET['order_field']));
        }

        /**
         * get the direction of the sorting process
         *
         * asc -> by increasing
         *      OR
         * desc -> by decreasing
         */
        $orderDirection = 'asc';
        if (isset($_GET['order_direction'])) {
            $orderDirection = htmlspecialchars(trim($_GET['order_direction']));
        }

        /**
         * get the offset
         *
         * (number of rows we should skip before getting data,
         * calculated based on the pagination page we are currently in
         * and limit of rows we can show on the one page)
         */
        $offset = $limit * ($page - 1);

        return [
            'limit' => $limit,
            'order_field' => $orderByField,
            'order_direction' => $orderDirection,
            'offset' => $offset
        ];
    }

    protected function _getAffiliatesAll() {
        $affiliates = $this->dataMapper->selectAllAffiliates();
        if ($affiliates === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all affiliates'
            ]);
        }
        foreach ($affiliates as &$affiliate) {
            $affiliate['name'] =
                "{$affiliate['city']}, {$affiliate['address']}";
        }

        $this->returnJson([
            'success' => true,
            'data' => $affiliates
        ]);
    }

    protected function _getDepartmentsAll() {
        $departments = $this->dataMapper->selectAllDepartments();
        if ($departments === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all affiliates'
            ]);
        }

        $this->returnJson([
            'success' => true,
            'data' => $departments
        ]);
    }

    protected function _getWorkersAll() {
        $workers = $this->dataMapper->selectAllWorkers();
        if ($workers === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all workers'
            ]);
        }
        foreach ($workers as &$worker) {
            $worker['name'] = $worker['name'] . " " . $worker['surname'];
        }
        $this->returnJson([
            'success' => true,
            'data' => $workers
        ]);
    }

    protected function _getServicesAll() {
        $services = $this->dataMapper->selectAllServices();
        if ($services === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all services'
            ]);
        }
        $this->returnJson([
            'success' => true,
            'data' => $services
        ]);
    }
}