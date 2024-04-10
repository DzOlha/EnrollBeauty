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

    #[NoReturn] public function returnJson(array $array, int $code = 200): void
    {
        http_response_code($code);
        $array += [
            'code' => $code
        ];
        echo json_encode($array);
        exit();
    }

    protected function _accessDenied(
        string $message = 'Access is denied to the requested resource!'
    ) {
        $this->returnJson([
            'error' => $message
        ], 403);
    }

    protected function _methodNotAllowed(array $allowedMethods)
    {
        $methods = implode(', ', $allowedMethods);
        $this->returnJson([
            'error' => "Method not allowed! Allowed ones: $methods"
        ], 405);
    }

    protected function _missingRequestFields()
    {
        $this->returnJson([
            'error' => "Missing request fields!"
        ], 400);
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

    /**
     * @return void
     *
     * url = /api/user/schedule/search
     * url = /api/open/schedule/search
     */
    protected function _searchSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $items = [
                'service_id' => htmlspecialchars(trim($_POST['service_id'])),
                'worker_id' => htmlspecialchars(trim($_POST['worker_id'])),
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'start_date' => htmlspecialchars(trim($_POST['start_date'])),
                'end_date' => htmlspecialchars(trim($_POST['end_date'])),
                'start_time' => htmlspecialchars(trim($_POST['start_time'])),
                'end_time' => htmlspecialchars(trim($_POST['end_time'])),
                'price_bottom' => htmlspecialchars(trim($_POST['price_bottom'])),
                'price_top' => htmlspecialchars(trim($_POST['price_top']))
            ];

            $items['start_date'] = date("Y-m-d", $items['start_date']);
            $items['end_date'] = date("Y-m-d", $items['end_date']);

            $startTime = \DateTime::createFromFormat('H:i', $items['start_time']);
            $endTime = \DateTime::createFromFormat('H:i', $items['end_time']);

            $items['start_time'] = $startTime ? $startTime->format('H:i:s') : '';
            $items['end_time'] = $endTime ? $endTime->format('H:i:s') : '';

            //var_dump($items);

            /**
             * Select all departments
             */
            $departments = $this->dataMapper->selectAllDepartments();
            if ($departments === false) {
                $this->returnJson([
                    'error' => 'There is error occurred while getting all departments'
                ], 404);
            }

            if (!$departments) {
                $this->returnJson([
                    'error' => 'There is no any departments yet!'
                ], 204);
            }

            $activeDepartment = null;
            if(!$items['service_id']) {
                $activeDepartment = $departments[0];
            } else {
                $activeDepartment = $this->dataMapper->selectDepartmentByServiceId(
                    $items['service_id']
                );
                if($activeDepartment === false) {
                    $this->returnJson([
                        'error' => 'The error occurred while getting the department for the service'
                    ], 404);
                }
            }

            $schedule = $this->dataMapper->selectSchedule(
                null, $items['service_id'], $items['worker_id'],
                $items['affiliate_id'], $items['start_date'], $items['end_date'],
                $items['start_time'], $items['end_time'],
                $items['price_bottom'], $items['price_top']
            );
            if($schedule === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting schedule'
                ], 404);
            }

            $this->returnJson([
                'success' => true,
                'data' => [
                    'schedule' => $schedule,
                    'departments' => $departments,
                    'active_department' => $activeDepartment,
                    'active_day' => $items['start_date'],
                    'end_day' => $items['end_date'],
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/service/get/workers/all
     * url = /api/open/service/get/workers/all
     */
    protected function _getWorkersForService()
    {
        $serviceId = 0;
        if (isset($_GET['service_id']) && $_GET['service_id'] !== '') {
            $serviceId = htmlspecialchars(trim($_GET['service_id']));
        }
        $result = $this->dataMapper->selectWorkersForService($serviceId);
        if ($result === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting workers for the selected service'
            ], 404);
        }

        foreach ($result as &$worker) {
            $worker['name'] = $worker['name'] . " " . $worker['surname'];
        }

        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }


    /**
     * @return void
     *
     * url = /api/user/worker/get/services/all
     * url = /api/open/worker/get/services/all
     */
    protected function _getServicesForWorker()
    {
        $workerId = 0;
        if (isset($_GET['worker_id']) && $_GET['worker_id'] !== '') {
            $workerId = htmlspecialchars(trim($_GET['worker_id']));
        }
        $result = $this->dataMapper->selectServicesForWorker($workerId);
        if ($result === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting services for the selected worker'
            ], 404);
        }
        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }
}