<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\DTO\Read\UserSocialReadDto;
use Src\Model\DTO\Write\UserWriteDto;
use Src\Model\Table\WorkersServiceSchedule;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;
use Src\Service\Hasher\impl\PasswordHasher;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PasswordHashValidator;
use Src\Service\Validator\impl\PasswordValidator;

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
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'name' => htmlspecialchars(trim($_POST['name'])),
                'surname' => htmlspecialchars(trim($_POST['surname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'password' => htmlspecialchars(trim($_POST['password'])),
                'confirm-password' => htmlspecialchars(trim($_POST['confirm-password']))
            ];
            $nameValidator = new NameValidator();
            $emailValidator = new EmailValidator();
            $passwordValidator = new PasswordValidator();

            /**
             * Name
             */
            $validName = $nameValidator->validate($items['name']);
            if (!$validName) {
                $this->returnJson([
                    'error' => 'Name must be at least 3 characters long and contain only letters'
                ]);
            }

            /**
             * Surname
             */
            $validSurname = $nameValidator->validate($items['surname']);
            if (!$validSurname) {
                $this->returnJson([
                    'error' => 'Surname must be at least 3 characters long and contain only letters'
                ]);
            }

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($items['email']);
            if (!$validEmail) {
                $this->returnJson([
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain'
                ]);
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if (!$validPass) {
                $this->returnJson([
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ]);
            }

            /**
             * Confirm Password
             */
            if ($items['password'] !== $items['confirm-password']) {
                $this->returnJson([
                    'error' => 'Passwords do not match'
                ]);
            }

            $passwordHash = PasswordHasher::hash($items['password']);

            /**
             * Check if the user with such email is already registered
             */
            $registeredBefore = $this->dataMapper->selectUserIdByEmail($items['email']);
            if ($registeredBefore) {
                $this->returnJson([
                    'error' => "The user with such email is already registered!"
                ]);
            }

            $this->dataMapper->beginTransaction();
            /**
             * Insert into 'users' table
             */
            $user = new UserWriteDto(
                $items['name'], $items['surname'], $items['email'], $passwordHash
            );

            $userId = $this->dataMapper->insertNewUser($user);
            if ($userId === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while creating a new user account!"
                ]);
            }

            /**
             * Insert into 'user_setting'
             */
            $inserted = $this->dataMapper->insertNewUserSetting($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while inserting user setting!"
                ]);
            }

            /**
             * Insert into 'user_photo'
             */
            $inserted = $this->dataMapper->insertNewUserPhoto($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while inserting user photo!"
                ]);
            }

            /**
             * Insert into 'user_social'
             */
            $inserted = $this->dataMapper->insertNewUserSocial($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while inserting user social!"
                ]);
            }

            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => "You successfully created a new user account!"
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'email' => htmlspecialchars(trim($_POST['email'])),
                'password' => htmlspecialchars(trim($_POST['password'])),
            ];
            $emailValidator = new EmailValidator();
            $passwordValidator = new PasswordValidator();

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($items['email']);
            if (!$validEmail) {
                $this->returnJson([
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain'
                ]);
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if (!$validPass) {
                $this->returnJson([
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ]);
            }

            /**
             * Get actual password hash
             */
            $actualPasswordHash = $this->dataMapper->selectUserPasswordByEmail($items['email']);
            if ($actualPasswordHash === false) {
                $this->returnJson([
                    'error' => 'There is no user with such email'
                ]);
            }

            /**
             * Check Password Equality
             */
            $hashValidator = new PasswordHashValidator($actualPasswordHash);
            $validPassword = $hashValidator->validate($items['password']);
            if (!$validPassword) {
                $this->returnJson([
                    'error' => 'The provided password does not match the one saved for the requested user!'
                ]);
            }

            /**
             * Select User ID for storing it into session
             */
            $userId = $this->dataMapper->selectUserIdByEmail($items['email']);
            if ($userId === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting user id!'
                ]);
            }

            /**
             * Store Users ID into session
             */
            SessionHelper::setUserSession($userId);
            $this->returnJson([
                'success' => true,
                'session' => $_SESSION['user_id']
            ]);
        }
    }

    /**
     * @return int|mixed|string
     */
    private function _getUserId()
    {
        $userId = 0;
        if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
            $userId = htmlspecialchars(trim($_GET['user_id']));
        } else {
            $sessionUserId = SessionHelper::getUserSession();
            if ($sessionUserId) {
                $userId = $sessionUserId;
            }
        }
        return $userId;
    }

    /**
     * @return void
     *
     * url = /api/user/getUserInfo
     */
    public function getUserInfo()
    {
        $userId = $this->_getUserId();
        /**
         * @var UserReadDto|false $result
         */
        $result = $this->dataMapper->selectUserInfoById($userId);

        if ($result) {
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting user's info"
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/getUserSocialNetworks
     */
    public function getUserSocialNetworks()
    {
        $userId = $this->_getUserId();
        /**
         * @var UserSocialReadDto|false $result
         */
        $result = $this->dataMapper->selectUserSocialById($userId);
        if ($result) {
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting user's social info"
            ]);
        }
    }

    private function _getLimitPageFieldOrderOffset(): array
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

    /**
     * @return void
     *
     * url = /api/user/getUserComingAppointments
     */
    public function getUserComingAppointments()
    {
        $userId = $this->_getUserId();
        $param = $this->_getLimitPageFieldOrderOffset();
        $result = $this->dataMapper->selectUserComingAppointments(
            $userId,
            $param['limit'],
            $param['offset'],
            $param['order_field'],
            $param['order_direction']
        );
        if ($result !== false) {
            $resultArrayTest = [
                [
                    'id' => 1,
                    'service_id' => 101,
                    'service_name' => 'Haircut',
                    'worker_id' => 201,
                    'worker_name' => 'John',
                    'worker_surname' => 'Doe',
                    'affiliate_id' => 301,
                    'affiliate_city' => 'New York',
                    'affiliate_address' => '123 Main St',
                    'start_datetime' => '2023-11-03 13:00:00',
                    'end_datetime' => '2023-11-03 14:00:00',
                    'price' => 50,
                    'currency' => 'USD',
                ],
                [
                    'id' => 2,
                    'service_id' => 102,
                    'service_name' => 'Massage',
                    'worker_id' => 202,
                    'worker_name' => 'Sarah',
                    'worker_surname' => 'Smith',
                    'affiliate_id' => 302,
                    'affiliate_city' => 'Los Angeles',
                    'affiliate_address' => '456 Elm St',
                    'start_datetime' => '2023-11-04 15:30:00',
                    'end_datetime' => '2023-11-04 16:30:00',
                    'price' => 80,
                    'currency' => 'USD',
                ],
                'totalRowsCount' => 2
            ];

            //$data = $result ? $result : $resultArrayTest;
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting user's coming appointments"
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/getWorkersForService
     */
    public function getWorkersForService()
    {
        $serviceId = 0;
        if (isset($_GET['service_id']) && $_GET['service_id'] !== '') {
            $serviceId = htmlspecialchars(trim($_GET['service_id']));
        }
        $result = $this->dataMapper->selectWorkersForService($serviceId);
        if ($result === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting workers for the selected service'
            ]);
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
     * url = /api/user/getServicesForWorker
     */
    public function getServicesForWorker()
    {
        $workerId = 0;
        if (isset($_GET['worker_id']) && $_GET['worker_id'] !== '') {
            $workerId = htmlspecialchars(trim($_GET['worker_id']));
        }
        $result = $this->dataMapper->selectServicesForWorker($workerId);
        if ($result === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting services for the selected worker'
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
     * url = /api/user/getServicesWorkersAffiliates
     */
    public function getServicesWorkersAffiliates()
    {
        $services = $this->dataMapper->selectAllServices();
        if ($services === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all services'
            ]);
        }

        $workers = $this->dataMapper->selectAllWorkers();
        if ($workers === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all workers'
            ]);
        }
        foreach ($workers as &$worker) {
            $worker['name'] = $worker['name'] . " " . $worker['surname'];
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
                'workers' => $workers,
                'affiliates' => $affiliates
            ]
        ]);
    }

    /**
     * @return void
     *
     * url = /api/user/getWorkersAll
     */
    public function getWorkersAll()
    {
        $result = $this->dataMapper->selectAllWorkers();
        if ($result === false) {
            $this->returnJson([
                'error' => 'The error occurred while getting all workers'
            ]);
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
     * url = /api/user/getServicesAll
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
     * url = /api/user/getInitialSchedule
     */
    public function getInitialSchedule()
    {
        /**
         * Select all departments for the tab menu
         */
        $departments = $this->dataMapper->selectAllDepartments();
        if ($departments === false) {
            $this->returnJson([
                'error' => 'There is error occurred while getting all departments'
            ]);
        }

        if (!$departments) {
            $this->returnJson([
                'error' => 'There is no any departments yet!'
            ]);
        }

        $defaultDepartment = $departments[0];

        $scheduleForDepartment = $this->dataMapper->selectSchedule(
            $defaultDepartment['id']
        );
        if ($scheduleForDepartment === false) {
            $this->returnJson([
                'error' => "The error occurred while getting schedule
                            for {$defaultDepartment['name']} department"

            ]);
        }

        $this->returnJson([
            'success' => true,
            'data' => [
                'schedule' => $scheduleForDepartment,
                'departments' => $departments,
                'active_department' => $defaultDepartment
            ]
        ]);
    }

    /**
     * @return void
     *
     * url = /api/user/searchSchedule
     */
    public function searchSchedule()
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
                ]);
            }

            if (!$departments) {
                $this->returnJson([
                    'error' => 'There is no any departments yet!'
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

            $schedule = $this->dataMapper->selectSchedule(
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

    public function orderServiceSchedule() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            /**
             * Get user id to work with
             */
            $userId = SessionHelper::getUserSession();
            if(!$userId) {
                if(!isset($_POST['email'])) {
                    $this->returnJson([
                        'error' => 'No user specified!'
                    ]);
                } else {
                    $userId = null;
                    $email = htmlspecialchars(trim($_POST['email']));
                }
            }

            $scheduleId = htmlspecialchars(trim($_POST['schedule_id']));

            /**
             * Get schedule details
             */
            $scheduleDetails = $this->dataMapper->selectWorkerScheduleItemById($scheduleId);
            if($scheduleDetails === false) {
                $this->returnJson([
                    'error' => 'There is error occurred while getting schedule details'
                ]);
            }
            if($scheduleDetails === null) {
                $this->returnJson([
                    'error' => 'There is no schedule with such id'
                ]);
            }

            /**
             * Get user email
             */
            if($userId) {
                $email = $this->dataMapper->selectUserEmailById($userId);
                if($email === false) {
                    $this->returnJson([
                        'error' => 'The error occurred while getting user email'
                    ]);
                }
                if($email === null) {
                    $this->returnJson([
                        'error' => 'There is no user email for the given id'
                    ]);
                }
            }

            /**
             * Check if there is no orders with such schedule_id
             */
            $exists = $this->dataMapper->selectOrderServiceByScheduleId($scheduleId);
            if($exists) {
                $this->returnJson([
                    'error' => 'The order for selected schedule item already exists'
                ]);
            }

            /**
             * Insert the new row into order_service
             */
            $service_id = explode('.', WorkersServiceSchedule::$service_id)[1];
            $worker_id = explode('.', WorkersServiceSchedule::$worker_id)[1];
            $affiliate_id = explode('.', WorkersServiceSchedule::$affiliate_id)[1];
            $day = explode('.', WorkersServiceSchedule::$day)[1];
            $start_time = explode('.', WorkersServiceSchedule::$start_time)[1];
            $end_time = explode('.', WorkersServiceSchedule::$end_time)[1];

            // Combine date and time strings
            $start_datetime_str = $scheduleDetails[$day] . ' '
                                . $scheduleDetails[$start_time];

            $end_datetime_str = $scheduleDetails[$day] . ' '
                                . $scheduleDetails[$end_time];

            // Create DateTime objects
            $_start_datetime = new \DateTime($start_datetime_str);
            $_end_datetime = new \DateTime($end_datetime_str);

            // Format the DateTime objects as needed
            $start_datetime = $_start_datetime->format('Y-m-d H:i:s');
            $end_datetime = $_end_datetime->format('Y-m-d H:i:s');

            $this->dataMapper->beginTransaction();

            $orderID = $this->dataMapper->insertOrderService(
                $scheduleId, $userId, $email, $scheduleDetails[$service_id],
                $scheduleDetails[$worker_id], $scheduleDetails[$affiliate_id],
                $start_datetime, $end_datetime,
            );
            if($orderID === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'There is error occurred while inserting order into database'
                ]);
            }

            /**
             * Update order_id in 'workers_service_schedule' table to mark that the schedule
             * is not available now
             */
            $updated = $this->dataMapper->updateOrderIdInWorkersServiceSchedule(
                $scheduleId, $orderID
            );
            if(!$updated) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating schedule availability'
                ]);
            }

            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => "You successfully created the order for the service",
            ]);
        }
    }
}