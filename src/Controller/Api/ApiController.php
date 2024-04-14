<?php

namespace Src\Controller\Api;

use Src\Controller\AbstractController;
use Src\DB\Database\MySql;
use Src\Helper\Http\HttpCode;
use Src\Helper\Http\HttpRequest;
use Src\Helper\Trimmer\impl\RequestTrimmer;
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

    public function returnJson(array $array, int $code = 200): void
    {
        if(array_key_exists('code', $array))
        {
            http_response_code($array['code']);
            echo json_encode($array);
            exit();
        }
        else {
            http_response_code($code);
            $array += [
                'code' => $code
            ];
            echo json_encode($array);
            exit();
        }
    }

    protected function _accessDenied(
        string $message = 'Access is denied to the requested resource!'
    ): void {
        $this->returnJson([
            'error' => $message
        ], HttpCode::forbidden());
    }

    protected function _notAuthorizedUser(
        string $message = 'Not authorized user!'
    ): void {
        $this->returnJson([
            'error' => $message
        ], HttpCode::unauthorized());
    }

    protected function _methodNotAllowed(array $allowedMethods): void
    {
        $methods = implode(', ', $allowedMethods);
        $this->returnJson([
            'error' => "Method not allowed! Allowed ones: $methods"
        ], HttpCode::methodNotAllowed());
    }

    protected function _missingRequestFields(): void
    {
        $this->returnJson([
            'error' => "Missing request fields!"
        ], HttpCode::badRequest());
    }

    protected function _getLimitPageFieldOrderOffset(): array
    {
        $trimmer = new RequestTrimmer();
        /**
         * get limits of displaying rows
         */
        $limit = 10;
        if (isset($_GET['limit'])) {
            $limit = (int)$trimmer->in($_GET['limit']);
        }

        /**
         * get number of the pagination page that should be populated with the data
         */
        $page = 1;
        if (isset($_GET['page'])) {
            $page = (int)$trimmer->in($_GET['page']);
        }

        /**
         * get the name of the column (field) the data should be sorted by
         */
        $orderByField = 'id';
        if (isset($_GET['order_field'])) {
            $orderByField = $trimmer->in($_GET['order_field']);
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
            $orderDirection = $trimmer->in($_GET['order_direction']);
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

    protected function _getAffiliatesAll()
    {
        if(HttpRequest::method() === 'GET')
        {
            $trimmer = new RequestTrimmer();

            $affiliates = $this->dataMapper->selectAllAffiliates();
            if ($affiliates === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting all affiliates'
                ], HttpCode::notFound());
            }
            foreach ($affiliates as &$affiliate) {
                $affiliate['name'] = $trimmer->out(
                    "{$affiliate['city']}, {$affiliate['address']}"
                );
            }

            $this->returnJson([
                'success' => true,
                'data' => $affiliates
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    protected function _getDepartmentsAll()
    {
        if(HttpRequest::method() === 'GET')
        {
            $departments = $this->dataMapper->selectAllDepartments();
            if ($departments === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting all affiliates'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $departments
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    protected function _getWorkersAll()
    {
        if(HttpRequest::method() === 'GET')
        {
            $trimmer = new RequestTrimmer();

            $workers = $this->dataMapper->selectAllWorkers();
            if ($workers === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting all workers'
                ], HttpCode::notFound());
            }
            foreach ($workers as &$worker) {
                $worker['name'] = $trimmer->out(
                    $worker['name'] . " " . $worker['surname']
                );
            }
            $this->returnJson([
                'success' => true,
                'data' => $workers
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    protected function _getServicesAll()
    {
        if(HttpRequest::method() === 'GET')
        {
            $services = $this->dataMapper->selectAllServices();
            if ($services === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting all services'
                ], HttpCode::notFound());
            }
            $this->returnJson([
                'success' => true,
                'data' => $services
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/schedule/search
     * url = /api/open/schedule/search
     */
    protected function _searchSchedule()
    {
        if (HttpRequest::method() == 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());

            $items = [
                'service_id' => $request->get('service_id'),
                'worker_id' => $request->get('worker_id'),
                'affiliate_id' => $request->get('affiliate_id'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'start_time' => $request->get('start_time'),
                'end_time' => $request->get('end_time'),
                'price_bottom' => $request->get('price_bottom'),
                'price_top' => $request->get('price_top'),
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
                ], HttpCode::notFound());
            }

            if (!$departments) {
                $this->returnJson([
                    'error' => 'There is no any departments yet!'
                ], HttpCode::noContent());
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
                    ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
        else {
            $this->_methodNotAllowed(['POST']);
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
        if (HttpRequest::method() == 'GET')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['service_id'])) {
                $this->_missingRequestFields();
            }
            $serviceId = $request->get('service_id');

            $result = $this->dataMapper->selectWorkersForService($serviceId);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting workers for the selected service'
                ], HttpCode::notFound());
            }

            foreach ($result as &$worker) {
                $worker['name'] = $trimmer->out(
                    $worker['name'] . " " . $worker['surname']
                );
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }


    /**
     * @return void
     *
     * url = /api/user/worker/get/services/all
     * url = /api/open/worker/get/services/all
     */
    protected function _getServicesForWorker()
    {
        if (HttpRequest::method() == 'GET')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['worker_id'])) {
                $this->_missingRequestFields();
            }
            $workerId = $request->get('worker_id');

            $result = $this->dataMapper->selectServicesForWorker($workerId);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting services for the selected worker'
                ], HttpCode::notFound());
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }
}