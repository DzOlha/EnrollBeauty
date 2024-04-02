<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Builder\impl\UrlBuilder;
use Src\Helper\Email\UserEmailHelper;
use Src\Helper\Email\WorkerEmailHelper;
use Src\Helper\Uploader\impl\FileUploader;
use Src\Service\Generator\impl\ImageNameGenerator;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\WorkerDataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\Entity\Gender;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\Worker\WorkerAuthService;
use Src\Service\Sender\impl\email\EmailSender;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\impl\MailgunService;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\FileSizeValidator;
use Src\Service\Validator\impl\FileTypeValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PhotoValidator;
use Src\Service\Validator\impl\SocialNetworksUrlValidator;

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

    public function getTypeDataMapper(): DataMapper
    {
        return new WorkerDataMapper(new WorkerDataSource(MySql::getInstance()));
    }

    public function checkPermission(): void
    {
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/service/
     */
    public function service()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/service/add
             */
            if ($this->url[3] === 'add') {
                $this->_addService();
            }

            /**
             * url = /api/worker/service/edit
             */
            if ($this->url[3] === 'edit') {
                $this->_editService();
            }

            /**
             * url = /api/worker/service/delete
             */
            if ($this->url[3] === 'delete') {
                $this->_deleteService();
            }

            /**
             * url = /api/worker/service/get/
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/service/get/one
                     */
                    if ($this->url[4] === 'one') {
                        $this->_getServiceById();
                    }
                    /**
                     * url = /api/worker/service/get/all
                     */
                    if ($this->url[4] === 'all') {
                        $this->_getServicesAll();
                    }

                    /**
                     * url = /api/worker/service/get/all-with-departments
                     */
                    if ($this->url[4] === 'all-with-departments') {
                        $this->_getServicesAllWithDepartments();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/affiliate/
     */
    public function affiliate()
    {
        if (isset($this->url[3])) {
//                /**
//                 * url = /api/worker/affiliate/add
//                 */
//                if($this->url[3] === 'add') {
//                    $this->_addAffiliate();
//                }
//
            /**
             * url = /api/worker/affiliate/get/
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/affiliate/get/all
                     */
                    if ($this->url[4] === 'all') {
                        $this->_getAffiliatesAll();
                    }
                }
            }
        }

    }

    /**
     * @return void
     *
     * url = /api/worker/schedule/
     */
    public function schedule()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/schedule/add
             */
            if ($this->url[3] === 'add') {
                $this->_addSchedule();
            }

            /**
             * url = /api/worker/schedule/edit
             */
            if ($this->url[3] === 'edit') {
                $this->_editSchedule();
            }

            /**
             * url = /api/worker/schedule/delete
             */
            if ($this->url[3] === 'delete') {
                $this->_deleteSchedule();
            }

            /**
             * url = /api/worker/schedule/search
             */
            if ($this->url[3] === 'search') {
                $this->_searchSchedule();
            }

            /**
             * url = /api/worker/schedule/get/
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/schedule/get/busy-time-intervals
                     */
                    if ($this->url[4] === 'busy-time-intervals') {
                        $this->_getBusyTimeIntervals();
                    }

                    /**
                     * url = /api/worker/schedule/get/edit-busy-time-intervals
                     */
                    if ($this->url[4] === 'edit-busy-time-intervals') {
                        $this->_getEditBusyTimeIntervals();
                    }

                    /**
                     * url = /api/worker/schedule/get/one
                     */
                    if ($this->url[4] === 'one') {
                        $this->_getOneSchedule();
                    }
                }
            }

        }

    }

    /**
     * @return void
     *
     * url = /api/worker/profile/
     */
    public function profile()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/profile/get
             */
            if ($this->url[3] === 'get') {
                $this->_getWorker();
            }

            /**
             *  url = /api/worker/profile/service-pricing/
             */
            if ($this->url[3] === 'service-pricing') {
                if (isset($this->url[4])) {
                    /**
                     *  url = /api/worker/profile/service-pricing/get/
                     */
                    if ($this->url[4] === 'get') {
                        if (isset($this->url[5])) {
                            /**
                             *  url = /api/worker/profile/service-pricing/get/all
                             */
                            if ($this->url[5] === 'all') {
                                $this->_getServicePricing();
                            }
                        }
                    }

                    /**
                     *  url = /api/worker/profile/service-pricing/add
                     */
                    if ($this->url[4] === 'add') {
                        $this->_addServicePricing();
                    }

                    /**
                     *  url = /api/worker/profile/service-pricing/edit
                     */
                    if ($this->url[4] === 'edit') {
                        $this->_editServicePricing();
                    }

                    /**
                     *  url = /api/worker/profile/service-pricing/delete
                     */
                    if ($this->url[4] === 'delete') {
                        $this->_deleteServicePricing();
                    }
                }
            }

            /**
             *  url = /api/worker/profile/service/
             */
            if ($this->url[3] === 'service') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/profile/service/get/
                     */
                    if ($this->url[4] === 'get') {
                        if (isset($this->url[5])) {
                            /**
                             * url = /api/worker/profile/service/get/all
                             */
                            if ($this->url[5] === 'all') {
                                $this->_getServicesAllForWorker();
                            }
                        }
                    }
                }
            }

            /**
             * url = /api/worker/profile/id
             */
            if ($this->url[3] === 'id') {
                $this->_getCurrentWorkerId();
            }

            /**
             * url = /api/worker/profile/personal-info/
             */
            if ($this->url[3] === 'personal-info') {
                /**
                 * url = /api/worker/profile/personal-info/get
                 */
                if ($this->url[4] === 'get') {
                    $this->_getWorkerPersonalInformation();
                }

                /**
                 * url = /api/worker/profile/personal-info/edit
                 */
                if ($this->url[4] === 'edit') {
                    $this->_editWorkerPersonalInformation();
                }
            }

            /**
             * url = /api/worker/profile/social/
             */
            if ($this->url[3] === 'social') {
                /**
                 * url = /api/worker/profile/social/get
                 */
                if ($this->url[4] === 'get') {
                    if(isset($this->url[5])) {
                        /**
                         * url = /api/worker/profile/social/get/all
                         */
                        if($this->url[5] === 'all') {
                            $this->_getWorkerSocialNetworksAll();
                        }
                    }
                }

                /**
                 * url = /api/worker/profile/social/edit
                 */
                if ($this->url[4] === 'edit') {
                    if(isset($this->url[5])) {
                        /**
                         * url = /api/worker/profile/social/edit/all
                         */
                        if($this->url[5] === 'all') {
                            $this->_editWorkerSocialNetworksAll();
                        }
                    }
                }
            }
        }

    }

    /**
     * @return void
     *
     * url = /api/worker/order/
     */
    public function order()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/order/service
             */
            if ($this->url[3] === 'service') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/order/service/cancel
                     */
                    if ($this->url[4] === 'cancel') {
                        $this->_cancelServiceOrder();
                    }

                    /**
                     * url = /api/worker/order/service/complete
                     */
                    if ($this->url[4] === 'complete') {
                        $this->_completeServiceOrder();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/department/
     */
    public function department()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/department/get
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/department/get/all
                     */
                    if ($this->url[4] === 'all') {
                        $this->_getDepartmentsAll();
                    }
                }
            }
        }
    }


    /**
     * @return void
     *
     * url = /api/worker/position/
     */
    public function position()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/position/get
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/position/get/one
                     */
                    if ($this->url[4] === 'one') {
                        $this->_getPositionForCurrentWorker();
                    }
                }
            }
        }
    }


    /**
     * @return void
     *
     * url = /api/worker/role/
     */
    public function role()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/role/get
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/worker/role/get/one
                     */
                    if ($this->url[4] === 'one') {
                        $this->_getRoleForCurrentWorker();
                    }
                }
            }
        }
    }

    private function _getWorkerId()
    {
        $workerId = 0;
        if (isset($_GET['worker_id']) && $_GET['worker_id'] !== '') {
            $workerId = htmlspecialchars(trim($_GET['worker_id']));
        } else {
            if (isset($_POST['worker_id']) && $_POST['worker_id'] !== '') {
                $workerId = htmlspecialchars(trim($_POST['worker_id']));
            } else {
                $sessionWorkerId = SessionHelper::getWorkerSession();
                if ($sessionWorkerId) {
                    $workerId = $sessionWorkerId;
                }
            }
        }
        return $workerId;
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/get
     */
    protected function _getWorker()
    {
        $workerId = $this->_getWorkerId();
        /**
         *  [
         *      'name' =>
         *      'surname' =>
         *      'email' =>
         * ]
         */
        $result = $this->dataMapper->selectWorkerInfoById($workerId);

        if ($result) {
            $this->returnJson([
                'success' => true,
                'data'    => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting worker's info"
            ], 404);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/affiliate/get/all
     */
    protected function _getAffiliatesAll()
    {
        parent::_getAffiliatesAll();
    }

    /**
     * @return void
     *
     * url = /api/worker/departments/get/all
     */
    protected function _getDepartmentsAll()
    {
        parent::_getDepartmentsAll();
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service/get/all
     */
    protected function _getServicesAllForWorker()
    {
        $workerId = $this->_getWorkerId();
        $services = $this->dataMapper->selectServicesForWorker(
            $workerId
        );
        if ($services === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all services'
            ], 404);
        }

        $this->returnJson([
            'success' => true,
            'data'    => $services
        ]);
    }

    protected function _searchSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $workerId = $this->_getWorkerId();
            $items = [
                'service_id'   => htmlspecialchars(trim($_POST['service_id'])),
                'worker_id'    => $workerId,
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'start_date'   => htmlspecialchars(trim($_POST['start_date'])),
                'end_date'     => htmlspecialchars(trim($_POST['end_date'])),
                'start_time'   => htmlspecialchars(trim($_POST['start_time'])),
                'end_time'     => htmlspecialchars(trim($_POST['end_time'])),
                'price_bottom' => htmlspecialchars(trim($_POST['price_bottom'])),
                'price_top'    => htmlspecialchars(trim($_POST['price_top'])),
                'only_ordered' => htmlspecialchars(trim($_POST['only_ordered'])),
                'only_free'    => htmlspecialchars(trim($_POST['only_free']))
            ];

            $items['only_ordered'] = !($items['only_ordered'] === 'false');
            $items['only_free'] = !($items['only_free'] === 'false');

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
            $departments = $this->dataMapper->selectDepartmentsForWorker($items['worker_id']);
            if ($departments === false) {
                $this->returnJson([
                    'error' => 'There is error occurred while getting all departments'
                ], 404);
            }

            if (!$departments) {
//                $this->returnJson([
//                    'error' => 'There is no any departments yet for the current worker!'
//                ]);
                $this->returnJson([
                    'success' => true
                ]);
            }

            $activeDepartment = null;
            if (!$items['service_id']) {
                $activeDepartment = $departments[0];
            } else {
                $activeDepartment = $this->dataMapper->selectDepartmentByServiceId(
                    $items['service_id']
                );
                if ($activeDepartment === false) {
                    $this->returnJson([
                        'error' => 'The error occurred while getting the department for the service'
                    ], 404);
                }
            }

            $scheduleOrdered = [];
            if ($items['only_ordered']) {
                $scheduleOrdered = $this->dataMapper->selectWorkerOrderedSchedule(
                    null, $items['service_id'], $items['worker_id'],
                    $items['affiliate_id'], $items['start_date'], $items['end_date'],
                    $items['start_time'], $items['end_time'],
                    $items['price_bottom'], $items['price_top']
                );
                if ($scheduleOrdered === false) {
                    $this->returnJson([
                        'error' => 'An error occurred while getting ordered schedules!'
                    ], 404);
                }
            }
            $scheduleFree = [];
            if ($items['only_free']) {
                $scheduleFree = $this->dataMapper->selectWorkerFreeSchedule(
                    null, $items['service_id'], $items['worker_id'],
                    $items['affiliate_id'], $items['start_date'], $items['end_date'],
                    $items['start_time'], $items['end_time'],
                    $items['price_bottom'], $items['price_top']
                );
                if ($scheduleFree === false) {
                    $this->returnJson([
                        'error' => 'An error occurred while getting free schedules!'
                    ], 404);
                }
            }

            $schedule = array_merge($scheduleOrdered, $scheduleFree);

            //var_dump($schedule);
            $this->returnJson([
                'success' => true,
                'data'    => [
                    'schedule'          => $schedule,
                    'departments'       => $departments,
                    'active_department' => $activeDepartment,
                    'active_day'        => $items['start_date'],
                    'end_day'           => $items['end_date'],
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/order/service/cancel
     */
    protected function _cancelServiceOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = htmlspecialchars(trim($_POST['order_id']));
            $scheduleId = htmlspecialchars(trim($_POST['schedule_id']));

            $this->dataMapper->beginTransaction();

            /**
             * Get user information who ordered the appointment
             *
             *
             * user_id
             * email
             *
             */
            $user = $this->dataMapper->selectUserByOrderId($orderId);
            if ($user === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while getting info about the user placed the order!'
                ], 404);
            }

            $userNameSurname = [
                'name'    => 'Dear',
                'surname' => 'Customer'
            ];
            if ($user['user_id'] !== null) {
                /**
                 * @var UserReadDto $userNameSurname
                 */
                $userNameSurname = $this->dataMapper->selectUserInfoById($user['user_id']);
                if ($userNameSurname === false) {
                    $this->dataMapper->rollBackTransaction();
                    $this->returnJson([
                        'error' => 'The error occurred while getting name/surname of the user placed the order!'
                    ], 404);
                }
            }

            /**
             * Get order details
             *
             * name (of the service)
             * start_datetime
             */
            $order = $this->dataMapper->selectOrderDetails($orderId);
            if ($order === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while getting order details!'
                ], 404);
            }
            // Create a DateTime object from the datetime string
            $datetime = new \DateTime($order['start_datetime']);

            // Format the datetime as November 24, 15:00
            $formattedStartDatetime = $datetime->format('F j, H:i');
            $order['start_datetime'] = $formattedStartDatetime;

            /**
             * Changed the cancellation time of the service order
             */
            $canceled = $this->dataMapper->updateServiceOrderCanceledDatetimeById($orderId);
            if ($canceled === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating cancellation datetime of the order!'
                ], 404);
            }

            /**
             * Update the order_id in 'workers_service_schedule' table
             * to mark the schedule as available for choosing
             * (because previous person, who ordered it, canceled appointment)
             */
            $updatedOrderId = $this->dataMapper->updateOrderIdByScheduleId($scheduleId);
            if ($updatedOrderId === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating order id!'
                ], 404);
            }

            /**
             * Send the email to the user with the information about
             * the cancellation of their appointment
             */
            $emailSent = UserEmailHelper::sendLetterToInformUserAboutCancellation(
                $user['email'], $userNameSurname->name, $userNameSurname->surname, $order
            );
            if ($emailSent !== true) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while sending informational letter to the user who placed the canceled order!'
                ], 502);
            }

            $updatedScheduleItem = $this->dataMapper->selectWorkerScheduleById($scheduleId);
            if ($updatedScheduleItem === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'An error occurred while getting schedule item!'
                ], 404);
            }

            /**
             * Return success
             */
            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => 'You successfully canceled the appointment!',
                'data'    => $updatedScheduleItem
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/order/service/complete
     */
    protected function _completeServiceOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = htmlspecialchars(trim($_POST['order_id']));
            $scheduleId = htmlspecialchars(trim($_POST['schedule_id']));

            /**
             * Update completed datetime of the order
             */
            $updated = $this->dataMapper->updateCompletedDatetimeByOrderId($orderId);
            if ($updated === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating completed datetime of the order!'
                ], 404);
            }

            /**
             * Return success
             */
            $this->returnJson([
                'success' => 'You successfully marked the appointment as completed!',
                'data'    => [
                    'schedule_id' => $scheduleId
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/service/get/one
     */
    public function _getServiceById()
    {
        $serviceId = 0;
        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $serviceId = (int)htmlspecialchars(trim($_GET['id']));
        }

        /**
         * [
         *      id:
         *      name:
         *      department_id:
         * ]
         */
        $result = $this->dataMapper->selectServiceById($serviceId);
        if ($result === false) {
            $this->returnJson([
                'error' => 'An error occurred while getting service details!'
            ], 404);
        }

        $this->returnJson([
            'success' => true,
            'data'    => $result
        ]);
    }


    /**
     * @return void
     *
     * url = /api/worker/service/get/all-with-departments
     */
    protected function _getServicesAllWithDepartments()
    {
        $param = $this->_getLimitPageFieldOrderOffset();
        $services = $this->dataMapper->selectAllServicesWithDepartments(
            $param['limit'],
            $param['offset'],
            $param['order_field'],
            $param['order_direction']
        );
        if ($services === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all services'
            ], 404);
        }
        $this->returnJson([
            'success' => true,
            'data'    => $services
        ]);
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service-pricing/add
     */
    protected function _addServicePricing()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'worker_id'  => $this->_getWorkerId(),
                'service_id' => htmlspecialchars(trim($_POST['service_id'])),
                'price'      => htmlspecialchars(trim($_POST['price']))
            ];
            /**
             * Validate Price
             */
            if (!$items['price']) {
                $this->returnJson([
                    'error' => 'Price is the required field!'
                ], 422);
            }
            if ($items['price'] < 0) {
                $this->returnJson([
                    'error' => 'Price can not be negative number!'
                ], 422);
            }
            if (!is_int((int)$items['price']) && !is_double((double)$items['price'])) {
                $this->returnJson([
                    'error' => 'Invalid price number was provided!'
                ], 422);
            }

            /**
             * Check if the price already exists for the current worker_id, service_id
             */
            $pricingId = $this->dataMapper->selectWorkerServicePricingByIds(
                $items['worker_id'], $items['service_id']
            );
            if ($pricingId) {
                $this->returnJson([
                    'error' => 'The pricing for the selected service has already been added before!'
                ], 403);
            }

            /**
             * Insert new pricing into db
             */
            $insertedId = $this->dataMapper->insertWorkerServicePricing(
                $items['worker_id'], $items['service_id'], $items['price']
            );
            if ($insertedId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting new pricing into database!'
                ], 404);
            }

            $this->returnJson([
                'success' => 'You successfully added one more pricing!',
                'data'    => [
                    'id' => $insertedId
                ]
            ], 201);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service-pricing/edit
     */
    protected function _editServicePricing()
    {
        $items = [
            'worker_id'  => $this->_getWorkerId(),
            'service_id' => htmlspecialchars(trim($_POST['service_id'])),
            'price'      => htmlspecialchars(trim($_POST['price']))
        ];
        /**
         * Validate Price
         */
        if (!$items['price']) {
            $this->returnJson([
                'error' => 'Price is the required field!'
            ], 422);
        }
        if ($items['price'] < 0) {
            $this->returnJson([
                'error' => 'Price can not be negative number!'
            ], 422);
        }
        if (!is_int((int)$items['price']) && !is_double((double)$items['price'])) {
            $this->returnJson([
                'error' => 'Invalid price number was provided!'
            ], 422);
        }

        /**
         * Update pricing in db
         */
        $updated = $this->dataMapper->updateWorkerServicePricing(
            $items['worker_id'], $items['service_id'], $items['price']
        );
        if ($updated === false) {
            $this->returnJson([
                'error' => 'An error occurred while updating pricing details!'
            ], 404);
        }

        $updatedPricing = $this->dataMapper->selectWorkerServicePricing(
            $items['worker_id'], $items['service_id']
        );
        if ($updatedPricing === false) {
            $this->returnJson([
                'error' => 'An error occurred while getting updated service pricing!'
            ], 404);
        }

        $this->returnJson([
            'success' => 'You successfully updated info about selected pricing!',
            'data'    => $updatedPricing
        ]);
    }


    /**
     * @return void
     *
     * url = /api/worker/profile/service-pricing/delete
     */
    protected function _deleteServicePricing()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pricingId = (int)htmlspecialchars(trim($_POST['id']));
            /**
             * [
             *      0 => [
             *          id =>
             *      ]
             * ]
             */
            $existingOrders = $this->dataMapper->selectActiveOrdersByPricingId($pricingId);
            if ($existingOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting information about active orders'
                ], 404);
            }

            /**
             * If some active orders for the pricing were found,
             * we can not delete the pricing,
             * worker should complete the left orders
             * before removing pricing that is related to such orders
             */
            if ($existingOrders) {
                $this->returnJson([
                    'error' => 'You can not remove this pricing because there are left some upcoming orders with it'
                ], 404);
            }

            /**
             * Delete the pricing itself
             */
            $deleted = $this->dataMapper->deleteWorkerServicePricingById($pricingId);
            if ($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the pricing item!'
                ], 404);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the pricing!',
                'data'    => [
                    'id' => $pricingId
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service-pricing/get/all
     */
    protected function _getServicePricing()
    {
        $param = $this->_getLimitPageFieldOrderOffset();
        /**
         *  * [
         *      0 => [
         *          id =>
         *          name =>
         *          surname =>
         *          email =>
         *          position =>
         *          salary =>
         *          experience =>
         *      ]
         *      ....................
         * ]
         */
        $result = $this->dataMapper->selectAllWorkersServicePricing(
            $this->_getWorkerId(),
            $param['limit'],
            $param['offset'],
            $param['order_field'],
            $param['order_direction']
        );
        if ($result === false) {
            $this->returnJson([
                'error' => "The error occurred while getting data about pricing!"
            ], 404);
        }

        $this->returnJson([
            'success' => true,
            'data'    => $result
        ]);
    }

    /**
     * @return void
     *
     * Get time intervals for the selected day for the current user
     * to know when there are free time spots for placing new schedule item
     */
    protected function _getBusyTimeIntervals()
    {
        $day = null;
        if (isset($_GET['day']) && $_GET['day'] !== '') {
            $day = htmlspecialchars(trim($_GET['day']));
        }

        $workerId = $this->_getWorkerId();

        /**
         * [
         *      0 => [
         *          'start_time' =>
         *          'end_time' =>
         *      ]
         *  ........................
         * ]
         */
        $filledIntervals = $this->dataMapper->selectFilledTimeIntervalsByWorkerIdAndDay(
            $workerId, $day
        );
        if ($filledIntervals === false) {
            $this->returnJson([
                'error' => 'An error occurred while getting filled time intervals for the selected day!'
            ], 404);
        }
        $this->returnJson([
            'success' => true,
            'data'    => $filledIntervals
        ]);
    }


    protected function _getEditBusyTimeIntervals()
    {
        if (empty($_GET['day']) || empty($_GET['schedule_id'])) {
            $this->_missingRequestFields();
        }
        $day = htmlspecialchars(trim($_GET['day']));
        $scheduleId = htmlspecialchars(trim($_GET['schedule_id']));

        $workerId = $this->_getWorkerId();

        /**
         * [
         *      0 => [
         *          'start_time' =>
         *          'end_time' =>
         *      ]
         *  ........................
         * ]
         */
        $filledIntervals = $this->dataMapper->selectEditFilledTimeIntervalsByWorkerIdAndDay(
            $workerId, $day, $scheduleId
        );
        if ($filledIntervals === false) {
            $this->returnJson([
                'error' => 'An error occurred while getting edit filled time intervals for the selected day!'
            ], 404);
        }
        $this->returnJson([
            'success' => true,
            'data'    => $filledIntervals
        ]);
    }

    protected function _getOneSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (empty($_GET['schedule_id'])) {
                $this->_missingRequestFields();
            }
            $id = htmlspecialchars(trim($_GET['schedule_id']));

            $result = $this->dataMapper->selectWorkerScheduleById($id);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the schedule item!'
                ], 404);
            }

            $this->returnJson([
                'success' => true,
                'data'    => $result
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/addSchedule
     *
     * POST = [
     *      'service_id' =>
     *      'affiliate_id' =>
     *      'day' => yyyy-mm-dd
     *      'start_time' => hh:ii:ss
     *      'end_time' => hh:ii:ss
     * ]
     */
    protected function _addSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'worker_id'    => $this->_getWorkerId(),
                'service_id'   => htmlspecialchars(trim($_POST['service_id'])),
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'day'          => htmlspecialchars(trim($_POST['day'])),
                'start_time'   => htmlspecialchars(trim($_POST['start_time'])),
                'end_time'     => htmlspecialchars(trim($_POST['end_time']))
            ];

            /**
             * Validate start and end time
             */
            $startTime = \DateTime::createFromFormat('H:i:s', $items['start_time']);
            $endTime = \DateTime::createFromFormat('H:i:s', $items['end_time']);
            if ($startTime >= $endTime) {
                $this->returnJson([
                    'error' => 'Start time should be less than end time!'
                ], 422);
            }

            /**
             * Check if there is some schedules for the current worker
             * withing provided time interval and at the selected day
             */
            $scheduleExists = $this->dataMapper->selectScheduleForWorkerByDayAndTime(
                $items['worker_id'], $items['day'], $items['start_time'], $items['end_time']
            );
            if ($scheduleExists) {
                $this->returnJson([
                    'error' => 'There is an overlapping with another of your schedule items! Please, review your schedule for the selected day to choose available time intervals!'
                ], 422);
            }

            /**
             * Select id of the pricing item (by worker_id and service_id)
             */
            $priceId = $this->dataMapper->selectPriceIdByWorkerIdServiceId(
                $items['worker_id'], $items['service_id']
            );
            if ($priceId === false) {
                $this->returnJson([
                    'error' => 'There is no pricing for the selected worker and service!'
                ], 404);
            }

            /**
             * Insert new schedule
             */
            $inserted = $this->dataMapper->insertWorkerServiceSchedule(
                $priceId, $items['affiliate_id'],
                $items['day'], $items['start_time'], $items['end_time']
            );
            if ($inserted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting new schedule item!'
                ], 404);
            }
            $this->returnJson([
                'success' => 'You successfully added new schedule item!',
                'data'    => [
                    'service_id' => $items['service_id'],
                    'day'        => $items['day']
                ]
            ], 201);
        }
    }


    /**
     * @return void
     *
     * url = /api/worker/addSchedule
     *
     * POST = [
     *      'id' =>
     *      'service_id' =>
     *      'affiliate_id' =>
     *      'day' => yyyy-mm-dd
     *      'start_time' => hh:ii:ss
     *      'end_time' => hh:ii:ss
     * ]
     */
    protected function _editSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'id'           => htmlspecialchars(trim($_POST['id'])),
                'worker_id'    => $this->_getWorkerId(),
                'service_id'   => htmlspecialchars(trim($_POST['service_id'])),
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'day'          => htmlspecialchars(trim($_POST['day'])),
                'start_time'   => htmlspecialchars(trim($_POST['start_time'])),
                'end_time'     => htmlspecialchars(trim($_POST['end_time']))
            ];

            /**
             * Validate start and end time
             */
            $startTime = \DateTime::createFromFormat('H:i:s', $items['start_time']);
            $endTime = \DateTime::createFromFormat('H:i:s', $items['end_time']);
            if ($startTime >= $endTime) {
                $this->returnJson([
                    'error' => 'Start time should be less than end time!'
                ], 422);
            }

            /**
             * Check if there is some schedules for the current worker
             * withing provided time interval and at the selected day
             */
            $scheduleExists = $this->dataMapper->selectEditScheduleForWorkerByDayAndTime(
                $items['worker_id'], $items['day'], $items['start_time'],
                $items['end_time'], $items['id']
            );
            if ($scheduleExists) {
                $this->returnJson([
                    'error' => 'There is an overlapping with another of your schedule items! Please, review your schedule for the selected day to choose available time intervals!'
                ], 422);
            }

            /**
             * Select id of the pricing item (by worker_id and service_id)
             */
            $priceId = $this->dataMapper->selectPriceIdByWorkerIdServiceId(
                $items['worker_id'], $items['service_id']
            );
            if ($priceId === false) {
                $this->returnJson([
                    'error' => 'There is no pricing for the selected worker and service!'
                ], 404);
            }

            /**
             * Insert new schedule
             */
            $updated = $this->dataMapper->updateWorkerServiceSchedule(
                $items['id'], $priceId, $items['affiliate_id'],
                $items['day'], $items['start_time'], $items['end_time']
            );
            if ($updated === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating the schedule item!'
                ], 404);
            }

            /**
             * Select updated schedule item
             */
            $updatedSchedule = $this->dataMapper->selectWorkerScheduleById($items['id']);
            if ($updatedSchedule === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the updated schedule item!'
                ], 404);
            }

            $this->returnJson([
                'success' => 'You successfully updated the schedule item!',
                'data'    => $updatedSchedule
            ]);
        }
    }

    protected function _deleteSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['schedule_id'])) {
                $this->_missingRequestFields();
            }

            $id = htmlspecialchars(trim($_POST['schedule_id']));

            $deleted = $this->dataMapper->deleteWorkerScheduleItemById($id);
            if ($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deletion of the schedule item!'
                ], 404);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the schedule item!',
                'data'    => [
                    'schedule_id' => $id
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/service/add
     */
    protected function _addService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'service_name'  => htmlspecialchars(trim($_POST['service_name'])),
                'department_id' => htmlspecialchars(trim($_POST['department_id']))
            ];
            /**
             * Validate service name
             */
            if (!$items['service_name']) {
                $this->returnJson([
                    'error' => 'Service name can not be empty!'
                ], 422);
            }
            if (strlen($items['service_name']) < 3) {
                $this->returnJson([
                    'error' => 'Service name should be equal to or longer than 3 characters!'
                ], 422);
            }
            /**
             * Check if there is no service with such name in the selected department
             */
            $exists = $this->dataMapper->selectServiceIdByNameAndDepartmentId(
                $items['service_name'], $items['department_id']
            );
            if ($exists) {
                $this->returnJson([
                    'error' => 'The service with provided name already exists in the selected department!'
                ], 403);
            }

            /**
             * Insert into database new service record
             */
            $serviceId = $this->dataMapper->insertNewService(
                $items['service_name'], $items['department_id']
            );
            if ($serviceId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting new service into database!'
                ], 404);
            }
            $this->returnJson([
                'success' => "You successfully added new service '{$items['service_name']}'"
            ], 201);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/service/edit
     */
    protected function _editService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'service_id'    => htmlspecialchars(trim($_POST['service_id'])),
                'service_name'  => htmlspecialchars(trim($_POST['service_name'])),
                'department_id' => htmlspecialchars(trim($_POST['department_id']))
            ];
            /**
             * Validate service name
             */
            if (!$items['service_name']) {
                $this->returnJson([
                    'error' => 'Service name can not be empty!'
                ], 422);
            }
            if (strlen($items['service_name']) < 3) {
                $this->returnJson([
                    'error' => 'Service name should be longer than 3 characters!'
                ], 422);
            }
            /**
             * Check if there is no service with such name in the selected department
             */
            $exists = $this->dataMapper->selectServiceIdByNameAndDepartmentId(
                $items['service_name'], $items['department_id']
            );
            if ($exists) {
                $this->returnJson([
                    'error' => 'The service with provided name already exists in the selected department!'
                ], 403);
            }

            /**
             * Update in the database info about the service
             */
            $serviceId = $this->dataMapper->updateServiceById(
                $items['service_id'], $items['service_name'], $items['department_id']
            );
            if ($serviceId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating information about the service in the database!'
                ], 404);
            }

            $updatedService = $this->dataMapper->selectServiceWithDepartmentById($items['service_id']);
            if ($updatedService === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting updated service!'
                ], 404);
            }

            $this->returnJson([
                'success' => "You successfully updated the service '{$items['service_name']}'",
                'data'    => $updatedService
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/service/delete
     */
    protected function _deleteService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id'])) {
                //http_response_code(422);
                $this->returnJson([
                    'error' => 'Empty post request!'
                ], 400);
            }
            $serviceId = htmlspecialchars(trim($_POST['id']));

            /**
             * Check if there is no future order for the service
             * that we would like to delete
             */
            $activeOrders = $this->dataMapper->selectActiveOrdersByServiceId($serviceId);
            if ($activeOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting information about active orders for the provided service!'
                ], 404);
            }
            if ($activeOrders) {
                $this->returnJson([
                    'error' => 'You can not remove this service because there are left some upcoming orders with it'
                ], 404);
            }

            /**
             * Delete the service
             */
            $deleted = $this->dataMapper->deleteServiceById($serviceId);
            if ($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deletion of the service!'
                ], 404);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the service!',
                'data'    => [
                    'id' => $serviceId
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/get/id
     */
    protected function _getCurrentWorkerId()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = SessionHelper::getWorkerSession();
            if (!$id) {
                $this->returnJson([
                    'error' => 'Not authorized worker!'
                ], 401);
            }
            $this->returnJson([
                'success' => true,
                'data'    => [
                    'id' => $id
                ]
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/personal-info/get
     */
    protected function _getWorkerPersonalInformation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (empty($_GET['id'])) {
                $this->_missingRequestFields();
            }
            $workerId = htmlspecialchars(trim($_GET['id']));

            $result = $this->dataMapper->selectWorkerPersonalInformationById($workerId);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the user personal information!'
                ], 404);
            }
            $result['description'] = $result['description'] !== null
                                    ? html_entity_decode($result['description'])
                                    : '';
            $this->returnJson([
                'success' => true,
                'data'    => $result
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/personal-info/edit
     */
    protected function _editWorkerPersonalInformation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id']) || empty($_POST['name'])
                || empty($_POST['surname']) || empty($_POST['email'])
                || empty($_POST['gender']) || empty($_POST['age'])
                || !isset($_POST['experience']) || !isset($_POST['description']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                'id' => htmlspecialchars(trim($_POST['id'])),
                'name' => htmlspecialchars(trim($_POST['name'])),
                'surname' => htmlspecialchars(trim($_POST['surname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'gender' => htmlspecialchars(trim($_POST['gender'])),
                'age' => htmlspecialchars(trim($_POST['age'])),
                'experience' => htmlspecialchars(trim($_POST['experience'])),
                'description' => htmlspecialchars(trim($_POST['description'])),
            ];

            /**
             * Validate text fields
             */
            $valid = $this->validateEditWorkerPersonalInfoForm($items);
            if($valid !== true) {
                $this->returnJson($valid, 422);
            }

            /**
             * Validate the photo and set 'random_name'
             */
            $photoChanged = true;
            if(empty($_FILES['photo'])) {
                $photoChanged = false;
            } else {
                $validPhoto = PhotoValidator::validateImageAndSetRandomName($_FILES['photo']);
                if($validPhoto !== true) {
                    $this->returnJson($validPhoto, 422);
                }
            }

            /**
             * Update the worker personal information in the database
             */
            $this->dataMapper->beginTransaction();

            $newImage = $photoChanged === true
                        ? $_FILES['photo']['random_name']
                        : null;

            /**
             * Update textual info
             */
            $updatedText = $this->dataMapper->updateWorkerPersonalInfoById(
                $items['id'], $items['name'], $items['surname'], $items['email'],
                $items['gender'], $items['age'], $items['experience'], $items['description']
            );

            /**
             * Update photo
             */
            $updatedPhoto = true;
            if($newImage) {
                /**
                 * Check the old main photo
                 */
                $oldPhotoFilename = $this->dataMapper->selectWorkerMainPhotoByWorkerId($items['id']);
                if($oldPhotoFilename === false) {
                    $this->dataMapper->rollBackTransaction();
                    $this->returnJson([
                        'error' => "An error occurred while getting the current worker's photo"
                    ], 404);
                }

                /**
                 * Check if new photo differ from the old one
                 */
                if(!$oldPhotoFilename || $newImage !== $oldPhotoFilename) {
                    /**
                     * If yes -> update photo in db to the new one
                     */
                    $updatedPhoto = $this->dataMapper->updateWorkerMainPhotoByWorkerId(
                        $items['id'], $newImage
                    );
                    if($updatedPhoto === false) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => 'An error occurred while updating your main photo!'
                        ], 404);
                    }

                    /**
                     * Upload the new image into the folder
                     */
                    $uploader = new FileUploader();
                    $folderPath = WORKERS_PHOTO_FOLDER . "worker_{$items['id']}/";

                    $uploaded = $uploader->upload(
                        $_FILES['photo'], $_FILES['photo']['random_name'], $folderPath
                    );
                    if(!$uploaded) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => 'An error occurred while uploading your main photo into appropriate folder!'
                        ], 404);
                    }

                    /**
                     * Remove the old photo from the folder
                     */
                    $deleted = FileUploader::deleteFileFromFolder($folderPath, $oldPhotoFilename);
                    if($deleted === false) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => "An error occurred while deleting the old worker's main photo "
                        ], 404);
                    }
                }
            }

            if($updatedText === false
                && (
                    ($updatedPhoto === false && $newImage)
                    || ($updatedPhoto && !$newImage)
                )
            ) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'An error occurred while updating your personal info!'
                ], 404);
            }

            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => 'You successfully update your personal information!',
                'data' => [
                    'id' => $items['id']
                ]
            ]);
        } else {
            $this->_methodNotAllowed(['POST']);
        }
    }

    private function validateEditWorkerPersonalInfoForm($items)
    {
        $nameValidator = new NameValidator();
        $emailValidator = new EmailValidator();
        /**
         * Name
         */
        $validName = $nameValidator->validate($items['name']);
        if (!$validName) {
            return [
                'error' => 'Name must be at least 3 characters long and contain only letters'
            ];
        }

        /**
         * Surname
         */
        $validSurname = $nameValidator->validate($items['surname']);
        if (!$validSurname) {
            return [
                'error' => 'Surname must be at least 3 characters long and contain only letters'
            ];
        }

        /**
         * Email
         */
        $validEmail = $emailValidator->validate($items['email']);
        if (!$validEmail) {
            return [
                'error' => 'Please enter an email address in the format myemail@mailservice.domain'
            ];
        }


        /**
         * Gender
         */
        if($items['gender']) {
            if(
                $items['gender'] !== Gender::$MALE
                && $items['gender'] !== Gender::$FEMALE
                && $items['gender'] !== Gender::$OTHER
            ) {
                return [
                    'error' => 'Invalid gender selected! It should be Male, Female, or Other'
                ];
            }
        } else {
            $items['gender'] = null;
        }

        /**
         * Age
         */
        if(!$items['age']) {
            return [
                'error' => 'Age is required field!'
            ];
        }
        if($items['age'] < 14 || $items['age'] > 80) {
            return [
                'error' => "The worker's age should be from 14 to 80 years!"
            ];
        }

        /**
         * Years of experience
         */
        if(!$items['experience']) {
            return [
                'error' => "Years of worker's experience is required field!"
            ];
        }
        if($items['experience'] < 0 || $items['experience'] > 66) {
            return [
                'error' => "The years of the worker's experience should be from 0 to 66 years!"
            ];
        }

        /**
         * Description
         */
        if($items['description']) {
            if(strlen($items['description']) <= 10) {
                return [
                    'error' => 'The description field should be longer than 10 characters!'
                ];
            }
        }

        return true;
    }

    /**
     * @return void
     *
     * url = /api/worker/position/get/one
     */
    protected function _getPositionForCurrentWorker()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $workerId = SessionHelper::getWorkerSession();
            if (!$workerId) {
                $this->returnJson([
                    'error' => 'Not authorized worker'
                ], 401);
            }

            $result = $this->dataMapper->selectPositionIdNameByWorkerId($workerId);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting position for the current worker!'
                ], 404);
            }
            $this->returnJson([
                'success' => true,
                'data'    => [
                    0 => $result
                ]
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/role/get/one
     */
    protected function _getRoleForCurrentWorker()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $workerId = SessionHelper::getWorkerSession();
            if (!$workerId) {
                $this->returnJson([
                    'error' => 'Not authorized worker'
                ], 401);
            }

            $result = $this->dataMapper->selectRoleIdNameByWorkerId($workerId);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting role for the current worker!'
                ], 404);
            }
            $this->returnJson([
                'success' => true,
                'data'    => [
                    0 => $result
                ]
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/social/get/all
     */
    protected function _getWorkerSocialNetworksAll()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->_missingRequestFields();
            }
            $id = htmlspecialchars(trim($_GET['id']));
            $result = $this->dataMapper->selectWorkerSocialNetworksByWorkerId($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting your social networks!'
                ], 404);
            }

            /**
             * Decode all html entity of the url
             */
            foreach ($result as &$link) {
                if(!$link) continue;
                $link = html_entity_decode($link);
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/social/edit/all
     */
    protected function _editWorkerSocialNetworksAll()
    {
        /**
         * {
         *      Instagram
         *      Facebook
         *      TikTok
         *      YouTube
         *      LinkedIn
         *      Github
         *      Telegram
         * }
         */
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['id'])) {
                $this->_missingRequestFields();
            }
            $rowId = htmlspecialchars(trim($_POST['id']));

            $items = [
                'Instagram' => htmlspecialchars(trim($_POST['Instagram'])),
                'Facebook' => htmlspecialchars(trim($_POST['Facebook'])),
                'TikTok' => htmlspecialchars(trim($_POST['TikTok'])),
                'YouTube' => htmlspecialchars(trim($_POST['YouTube'])),
                'LinkedIn' => htmlspecialchars(trim($_POST['LinkedIn'])),
                'Github' => htmlspecialchars(trim($_POST['Github'])),
                'Telegram' => htmlspecialchars(trim($_POST['Telegram'])),
            ];

            /**
             * Validate all urls
             */
            $validator = new SocialNetworksUrlValidator();
            $valid = $validator->validateAll($items);
            if($valid !== true) {
                $this->returnJson([
                    'error' => $valid
                ], 422);
            }

            /**
             * Update the social networks of the worker in database
             */
            $updated = $this->dataMapper->updateWorkerSocialById(
                $rowId, $items
            );
            if($updated === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating your social networks!'
                ], 404);
            }

            $this->returnJson([
                'success' => 'You successfully updated your social networks!',
                'data' => $items
            ]);
        }
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }
}