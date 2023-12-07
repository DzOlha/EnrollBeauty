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
     * url = /api/worker/changePassword
     */
    public function changePassword() {
        $changed =  $this->authService->changeWorkerPassword();
        if(isset($changed['success'])) {
            SessionHelper::removeRecoveryCodeSession();
        }
       $this->returnJson($changed);
    }

    /**
     * @return void
     *
     * url = /api/worker/login
     */
    public function login() {
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
     * url = /api/worker/getWorkerInfo
     */
    public function getWorkerInfo() {
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
     * url = /api/worker/getServicesAffiliates
     */
    public function getServicesAffiliates() {
        $services = $this->dataMapper->selectServicesForWorker(
            SessionHelper::getWorkerSession()
        );
        if ($services === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all services'
            ]);
        }

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
            'data' => [
                'services' => $services,
                'affiliates' => $affiliates
            ]
        ]);
    }

    public function searchSchedule() {
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

            $schedule = $this->dataMapper->selectWorkerSchedule(
                null, $items['service_id'], $items['worker_id'],
                $items['affiliate_id'], $items['start_date'], $items['end_date'],
                $items['start_time'], $items['end_time'],
                $items['price_bottom'], $items['price_top']
            );
            if($schedule === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting schedule'
                ]);
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
     * @throws \Exception
     *
     * url = /api/worker/cancelServiceOrder
     */
    public function cancelServiceOrder() {
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
     * url = /api/worker/completeServiceOrder
     */
    public function completeServiceOrder()
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
    public function getServicesAll()
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
     * url = /api/worker/addServicePricing
     */
    public function addServicePricing() {
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
     * url = /api/worker/getServicePricing
     */
    public function getServicePricing() {
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
    public function addSchedule() {
        if (!SessionHelper::getWorkerSession()) {
            $this->_accessDenied();
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'service_id' => htmlspecialchars(trim($_POST['service_id'])),
                'affiliate_id' => htmlspecialchars(trim($_POST['affiliate_id'])),
                'day' => htmlspecialchars(trim($_POST['day'])),
                'start_time' => htmlspecialchars(trim($_POST['start_time'])),
                'end_time' => htmlspecialchars(trim($_POST['end_time']))
            ];


        }
    }
}