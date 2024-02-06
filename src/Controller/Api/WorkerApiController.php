<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Builder\impl\UrlBuilder;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\WorkerDataMapper;
use Src\Model\DataSource\extends\WorkerDataSource;
use Src\Model\DTO\Read\UserReadDto;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\Worker\WorkerAuthService;
use Src\Service\Sender\impl\email\EmailSender;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\impl\MailgunService;

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
            ]);
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
            ]);
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
                ]);
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
                    ]);
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
                    ]);
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
                    ]);
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
                ]);
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
                    ]);
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
                ]);
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
                ]);
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
                ]);
            }

            /**
             * Send the email to the user with the information about
             * the cancellation of their appointment
             */
            $emailSent = $this->_sendLetterToInformUserAboutCancellation(
                $user['email'], $userNameSurname->name, $userNameSurname->surname, $order
            );
            if ($emailSent !== true) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while sending informational letter to the user who placed the canceled order!'
                ]);
            }

            $updatedScheduleItem = $this->dataMapper->selectWorkerScheduleById($scheduleId);
            if ($updatedScheduleItem === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'An error occurred while getting schedule item!'
                ]);
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

    protected function _createLinkToLogin()
    {
        return ENROLL_BEAUTY_URL_HTTP_ROOT . 'web/user/auth/login';
    }

    protected function _sendLetterToInformUserAboutCancellation(
        $email, $name, $surname, $order
    )
    {
        $email = new Email(
            COMPANY_EMAIL,
            COMPANY_NAME,
            [$email],
            'order_canceled',
            EMAIL_WITH_LINK,
        );

        $loginUrl = $this->_createLinkToLogin();
        $email->populateWorkerWelcomeLetter(
            $loginUrl, $name, $surname, 'Enroll Beauty', 'Order Cancellation',
            "Your order '{$order['name']}' on {$order['start_datetime']} has been cancelled by the master. Please, log in to check your orders and schedule new appointments!",
            'Visit my account!'
        );

        $sender = new EmailSender($email, new MailgunService());
        $emailSent = $sender->send();

        return $emailSent;
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
                ]);
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
            ]);
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
            ]);
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
                ]);
            }
            if ($items['price'] < 0) {
                $this->returnJson([
                    'error' => 'Price can not be negative number!'
                ]);
            }
            if (!is_int((int)$items['price']) && !is_double((double)$items['price'])) {
                $this->returnJson([
                    'error' => 'Invalid price number was provided!'
                ]);
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
                ]);
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
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully added one more pricing!',
                'data'    => [
                    'id' => $insertedId
                ]
            ]);
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
            ]);
        }
        if ($items['price'] < 0) {
            $this->returnJson([
                'error' => 'Price can not be negative number!'
            ]);
        }
        if (!is_int((int)$items['price']) && !is_double((double)$items['price'])) {
            $this->returnJson([
                'error' => 'Invalid price number was provided!'
            ]);
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
            ]);
        }

        $updatedPricing = $this->dataMapper->selectWorkerServicePricing(
            $items['worker_id'], $items['service_id']
        );
        if ($updatedPricing === false) {
            $this->returnJson([
                'error' => 'An error occurred while getting updated service pricing!'
            ]);
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
                ]);
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
                ]);
            }

            /**
             * Delete the pricing itself
             */
            $deleted = $this->dataMapper->deleteWorkerServicePricingById($pricingId);
            if ($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the pricing item!'
                ]);
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
            $this->returnJsonError(
                "The error occurred while getting data about pricing!"
            );
        }

        foreach ($result as $key => &$value) {
            if ($key === 'totalRowsCount') continue;
            $datetime = new \DateTime($value['updated_datetime']);
            /**
             * Format the updated datetime as November 24, 15:00
             */
            $value['updated_datetime'] = $datetime->format('F j, H:i');
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
            ]);
        }
        $this->returnJson([
            'success' => true,
            'data'    => $filledIntervals
        ]);
    }


    protected function _getEditBusyTimeIntervals()
    {
        if (empty($_GET['day']) || empty($_GET['schedule_id'])) {
            $this->returnJson([
                'error' => 'Missing get fields!'
            ]);
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
            ]);
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
                $this->returnJson([
                    'error' => 'Missing schedule ID!'
                ]);
            }
            $id = htmlspecialchars(trim($_GET['schedule_id']));

            $result = $this->dataMapper->selectWorkerScheduleById($id);
            if ($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the schedule item!'
                ]);
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
                ]);
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
                ]);
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
                ]);
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
                ]);
            }
            $this->returnJson([
                'success' => 'You successfully added new schedule item!',
                'data'    => [
                    'service_id' => $items['service_id'],
                    'day'        => $items['day']
                ]
            ]);
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
                ]);
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
                ]);
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
                ]);
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
                    'error' => 'An error occurred while inserting new schedule item!'
                ]);
            }

            /**
             * Select updated schedule item
             */
            $updatedSchedule = $this->dataMapper->selectWorkerScheduleById($items['id']);
            if ($updatedSchedule === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the updated schedule item!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully updated the schedule item!',
                'data'    => $updatedSchedule
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
                ]);
            }
            if (strlen($items['service_name']) < 3) {
                $this->returnJson([
                    'error' => 'Service name should be longer than 3 characters!'
                ]);
            }
            /**
             * Check if there is no service with such name in the selected department
             */
            $exists = $this->dataMapper->selectServiceIdByNameAndDepartmentId(
                $items['service_name'], $items['department_id']
            );
            if ($exists === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting service with provided name!'
                ]);
            }
            if ($exists) {
                $this->returnJson([
                    'error' => 'The service with provided name already exists in the selected department!'
                ]);
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
                ]);
            }
            $this->returnJson([
                'success' => "You successfully added new service '{$items['service_name']}'"
            ]);
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
                ]);
            }
            if (strlen($items['service_name']) < 3) {
                $this->returnJson([
                    'error' => 'Service name should be longer than 3 characters!'
                ]);
            }
            /**
             * Check if there is no service with such name in the selected department
             */
            $exists = $this->dataMapper->selectServiceIdByNameAndDepartmentId(
                $items['service_name'], $items['department_id']
            );
            if ($exists === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting service with provided name!'
                ]);
            }
            if ($exists) {
                $this->returnJson([
                    'error' => 'The service with provided name already exists in the selected department!'
                ]);
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
                ]);
            }

            $updatedService = $this->dataMapper->selectServiceWithDepartmentById($items['service_id']);
            if ($updatedService === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting updated service!'
                ]);
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
                ]);
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
                ]);
            }
            if ($activeOrders) {
                $this->returnJson([
                    'error' => 'You can not remove this service because there are left some upcoming orders with it'
                ]);
            }

            /**
             * Delete the service
             */
            $deleted = $this->dataMapper->deleteServiceById($serviceId);
            if ($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deletion of the service!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the service!',
                'data'    => [
                    'id' => $serviceId
                ]
            ]);
        }
    }
}