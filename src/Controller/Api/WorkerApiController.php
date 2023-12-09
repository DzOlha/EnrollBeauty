<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Builder\impl\UrlBuilder;
use Src\Helper\Session\SessionHelper;
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

    public function getTypeDataMapper(): WorkerDataMapper
    {
        return new WorkerDataMapper(new WorkerDataSource(MySql::getInstance()));
    }

    /**
     * @return void
     *
     * url = /api/worker/auth/
     */
    public function auth() {
        if (isset($this->url[3])) {
            /**
             * url = /api/worker/auth/login
             */
            if ($this->url[3] === 'login') {
                $this->_login();
            }

            /**
             * url = /api/worker/auth/change-password
             */
            if ($this->url[3] === 'change-password') {
                $this->_changePassword();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/service/
     */
    public function service() {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                /**
                 * url = /api/worker/service/add
                 */
                if($this->url[3] === 'add') {
                    $this->_addService();
                }

                /**
                 * url = /api/worker/service/get/
                 */
                if($this->url[3] === 'get') {
                    if(isset($this->url[4])) {
                        /**
                         * url = /api/worker/service/get/all
                         */
                        if($this->url[4] === 'all') {
                            $this->_getServicesAll();
                        }
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
    public function affiliate() {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
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
                if($this->url[3] === 'get') {
                    if(isset($this->url[4])) {
                        /**
                         * url = /api/worker/affiliate/get/all
                         */
                        if($this->url[4] === 'all') {
                            $this->_getAffiliatesAll();
                        }
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
    public function schedule() {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                /**
                 * url = /api/worker/schedule/add
                 */
                if($this->url[3] === 'add') {
                    $this->_addSchedule();
                }

                /**
                 * url = /api/worker/schedule/search
                 */
                if($this->url[3] === 'search') {
                    $this->_searchSchedule();
                }

                /**
                 * url = /api/worker/schedule/get/
                 */
                if($this->url[3] === 'get') {
                    if(isset($this->url[4])) {
                        /**
                         * url = /api/worker/schedule/get/busy-time-intervals
                         */
                        if($this->url[4] === 'busy-time-intervals') {
                            $this->_getBusyTimeIntervals();
                        }
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
    public function profile() {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                /**
                 * url = /api/worker/profile/get
                 */
                if($this->url[3] === 'get') {
                    $this->_getWorker();
                }

                /**
                 *  url = /api/worker/profile/service-pricing/
                 */
                if($this->url[3] === 'service-pricing') {
                    if(isset($this->url[4])) {
                        /**
                         *  url = /api/worker/profile/service-pricing/get/
                         */
                        if($this->url[4] === 'get') {
                            if(isset($this->url[5])) {
                                /**
                                 *  url = /api/worker/profile/service-pricing/get/all
                                 */
                                if($this->url[5] === 'all') {
                                    $this->_getServicePricing();
                                }
                            }
                        }

                        /**
                         *  url = /api/worker/profile/service-pricing/add
                         */
                        if($this->url[4] === 'add') {
                           $this->_addServicePricing();
                        }
                    }
                }

                /**
                 *  url = /api/worker/profile/service/
                 */
                if($this->url[3] === 'service') {
                    if(isset($this->url[4])) {
                        /**
                         * url = /api/worker/profile/service/get/
                         */
                        if($this->url[4] === 'get') {
                            if(isset($this->url[5])) {
                                /**
                                 * url = /api/worker/profile/service/get/all
                                 */
                                if($this->url[5] === 'all') {
                                    $this->_getServicesAllForWorker();
                                }
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
    public function order() {
        $session = SessionHelper::getWorkerSession();
        if (!$session) {
            $this->_accessDenied();
        } else {
            if (isset($this->url[3])) {
                /**
                 * url = /api/worker/order/service
                 */
                if($this->url[3] === 'service') {
                    if(isset($this->url[4])) {
                        /**
                         * url = /api/worker/order/service/cancel
                         */
                        if($this->url[4] === 'cancel') {
                            $this->_cancelServiceOrder();
                        }

                        /**
                         * url = /api/worker/order/service/complete
                         */
                        if($this->url[4] === 'complete') {
                            $this->_completeServiceOrder();
                        }
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
    public function department() {
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
     * url = /api/worker/auth/change-password
     */
    protected function _changePassword() {
        $changed =  $this->authService->changeWorkerPassword();
        if(isset($changed['success'])) {
            SessionHelper::removeRecoveryCodeSession();
        }
        $this->returnJson($changed);
    }

    /**
     * @return void
     *
     * url = /api/worker/auth/login
     */
    protected function _login() {
        $this->returnJson(
            $this->authService->loginWorker()
        );
    }


    private function _getWorkerId()
    {
        $workerId = 0;
        if (isset($_GET['worker_id']) && $_GET['worker_id'] !== '') {
            $workerId = htmlspecialchars(trim($_GET['worker_id']));
        } else {
            if(isset($_POST['worker_id']) && $_POST['worker_id'] !== '') {
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
    protected function _getWorker() {
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
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
                'data' => $result
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
    protected function _getAffiliatesAll() {
        parent::_getAffiliatesAll();
    }

    /**
     * @return void
     *
     * url = /api/worker/departments/get/all
     */
    protected function _getDepartmentsAll() {
        parent::_getDepartmentsAll();
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service/get/all
     */
    protected function _getServicesAllForWorker() {
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
            'data' => $services
        ]);
    }



    protected function _searchSchedule() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $workerId = $this->_getWorkerId();
            $items = [
                'service_id' => htmlspecialchars(trim($_POST['service_id'])),
                'worker_id' => $workerId,
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'start_date' => htmlspecialchars(trim($_POST['start_date'])),
                'end_date' => htmlspecialchars(trim($_POST['end_date'])),
                'start_time' => htmlspecialchars(trim($_POST['start_time'])),
                'end_time' => htmlspecialchars(trim($_POST['end_time'])),
                'price_bottom' => htmlspecialchars(trim($_POST['price_bottom'])),
                'price_top' => htmlspecialchars(trim($_POST['price_top'])),
                'only_ordered' => htmlspecialchars(trim($_POST['only_ordered'])),
                'only_free' => htmlspecialchars(trim($_POST['only_free']))
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
            if(!$items['service_id']) {
                $activeDepartment = $departments[0];
            } else {
                $activeDepartment = $this->dataMapper->selectDepartmentByServiceId(
                    $items['service_id']
                );
                if($activeDepartment === false) {
                    $this->returnJson([
                        'error' => 'The error occurred while getting the department for the service'
                    ]);
                }
            }

            $scheduleOrdered = [];
            if($items['only_ordered']) {
                $scheduleOrdered = $this->dataMapper->selectWorkerOrderedSchedule(
                    null, $items['service_id'], $items['worker_id'],
                    $items['affiliate_id'], $items['start_date'], $items['end_date'],
                    $items['start_time'], $items['end_time'],
                    $items['price_bottom'], $items['price_top']
                );
                if($scheduleOrdered === false) {
                    $this->returnJson([
                        'error' => 'An error occurred while getting ordered schedules!'
                    ]);
                }
            }
            $scheduleFree = [];
            if($items['only_free']) {
                $scheduleFree = $this->dataMapper->selectWorkerFreeSchedule(
                    null, $items['service_id'], $items['worker_id'],
                    $items['affiliate_id'], $items['start_date'], $items['end_date'],
                    $items['start_time'], $items['end_time'],
                    $items['price_bottom'], $items['price_top']
                );
                if($scheduleFree === false) {
                    $this->returnJson([
                        'error' => 'An error occurred while getting free schedules!'
                    ]);
                }
            }

            $schedule = array_merge($scheduleOrdered, $scheduleFree);

            //var_dump($schedule);
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
     * url = /api/worker/order/service/cancel
     */
    protected function _cancelServiceOrder() {
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            if($user === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while getting info about the user placed the order!'
                ]);
            }

            $userNameSurname = [
                'name' => 'Dear',
                'surname' => 'Customer'
            ];
            if($user['user_id'] !== null) {
                /**
                 * @var UserReadDto $userNameSurname
                 */
                $userNameSurname = $this->dataMapper->selectUserInfoById($user['user_id']);
                if($userNameSurname === false) {
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
            if($order === false) {
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
            if($canceled === false) {
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
            if($updatedOrderId === false) {
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
            if($emailSent !== true) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while sending informational letter to the user who placed the canceled order!'
                ]);
            }

            /**
             * Return success
             */
            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => 'You successfully canceled the appointment!'
            ]);
        }
    }

    protected function _createLinkToLogin() {
        $builder = new UrlBuilder();
        $url = $builder->baseUrl(ENROLL_BEAUTY_URL_HTTP_ROOT)
                ->controllerType('web')
                ->controllerPrefix('user')
                ->controllerMethod('login')
            ->build();
        return $url;
    }

    protected function _sendLetterToInformUserAboutCancellation(
        $email, $name, $surname, $order
    ) {
        $email = new Email(
            'enroll@beauty.com',
            'Enroll Beauty',
            [$email],
            'order_canceled',
            SRC.'/Service/Sender/impl/email/templates/email_with_link.html',
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
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = htmlspecialchars(trim($_POST['order_id']));

            /**
             * Update completed datetime of the order
             */
            $updated = $this->dataMapper->updateCompletedDatetimeByOrderId($orderId);
            if($updated === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating completed datetime of the order!'
                ]);
            }

            /**
             * Return success
             */
            $this->returnJson([
                'success' => 'You successfully marked the appointment as completed!'
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/getServicesAll
     */
    protected function _getServicesAll()
    {
        $result = $this->dataMapper->selectAllServices();
        if ($result === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all services'
            ]);
        }

        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service-pricing/add
     */
    protected function _addServicePricing() {
        if(!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'worker_id' => $this->_getWorkerId(),
                'service_id' => htmlspecialchars(trim($_POST['service_id'])),
                'price' => htmlspecialchars(trim($_POST['price']))
            ];
            /**
             * Validate Price
             */
            if(!$items['price']) {
                $this->returnJson([
                    'error' => 'Price is the required field!'
                ]);
            }
            if($items['price'] < 0){
                $this->returnJson([
                    'error' => 'Price can not be negative number!'
                ]);
            }
            if(!is_int((int)$items['price']) && !is_double((double)$items['price'])) {
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
            if($pricingId) {
                $this->returnJson([
                    'error' => 'The pricing for the selected service has already been added before!'
                ]);
            }

            /**
             * Insert new pricing into db
             */
            $inserted = $this->dataMapper->insertWorkerServicePricing(
                $items['worker_id'], $items['service_id'], $items['price']
            );
            if($inserted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting new pricing into database!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully added one more pricing!'
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/worker/profile/service-pricing/get/all
     */
    protected function _getServicePricing() {
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
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
        if($result === false) {
            $this->returnJsonError(
                "The error occurred while getting data about pricing!"
            );
        }

        foreach ($result as $key => &$value) {
            if($key === 'totalRowsCount') continue;
            $datetime = new \DateTime($value['updated_datetime']);
            /**
             * Format the updated datetime as November 24, 15:00
             */
            $value['updated_datetime'] = $datetime->format('F j, H:i');
        }

        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * @return void
     *
     * Get time intervals for the selected day for the current user
     * to know when there are free time spots for placing new schedule item
     */
    protected function _getBusyTimeIntervals() {
        $day = null;
        if(isset($_GET['day']) && $_GET['day'] !== '') {
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
        if($filledIntervals === false) {
            $this->returnJson([
                'error' => 'An error occurred while getting filled time intervals for the selected day!'
            ]);
        }
        $this->returnJson([
            'success' => true,
            'data' => $filledIntervals
        ]);
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
    protected function _addSchedule() {
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'worker_id' => $this->_getWorkerId(),
                'service_id' => htmlspecialchars(trim($_POST['service_id'])),
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'day' => htmlspecialchars(trim($_POST['day'])),
                'start_time' => htmlspecialchars(trim($_POST['start_time'])),
                'end_time' => htmlspecialchars(trim($_POST['end_time']))
            ];

            /**
             * Validate start and end time
             */
            $startTime = \DateTime::createFromFormat('H:i:s', $items['start_time']);
            $endTime = \DateTime::createFromFormat('H:i:s', $items['end_time']);
            if($startTime >= $endTime) {
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
            if($scheduleExists) {
                $this->returnJson([
                    'error' => 'There is an overlapping with another of your schedule items! Please, review your schedule for the selected day to choose available time intervals!'
                ]);
            }

            /**
             * Insert new schedule
             */
            $inserted = $this->dataMapper->insertWorkerServiceSchedule(
                $items['worker_id'], $items['service_id'], $items['affiliate_id'],
                $items['day'], $items['start_time'], $items['end_time']
            );
            if($inserted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting new schedule item!'
                ]);
            }
            $this->returnJson([
                'success' => 'You successfully added new schedule item!',
                'data' => [
                    'service_id' => $items['service_id'],
                    'day' => $items['day']
                ]
            ]);
        }
    }
}