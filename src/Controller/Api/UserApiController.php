<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Email\UserEmailHelper;
use Src\Helper\Session\SessionHelper;
use Src\Helper\Uploader\impl\FileUploader;
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
use Src\Service\Validator\impl\PhotoValidator;
use Src\Service\Validator\impl\SocialNetworksUrlValidator;

class UserApiController extends ApiController
{
    protected AuthService $authService;

    /**
     * @param ?AuthService $authService
     */
    public function __construct(array $url, AuthService $authService = null)
    {
        parent::__construct($url);
        $this->authService = $authService ?? new UserAuthService($this->dataMapper);
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new UserDataMapper(new UserDataSource(MySql::getInstance()));
    }

    public function checkPermission(): void
    {
        if(!SessionHelper::getUserSession()) {
            $this->_accessDenied();
        }
    }

    /**
     * @return void
     *
     * url = /api/user/profile
     */
    public function profile() {
        if (isset($this->url[3])) {
            /**
             * url = /api/user/profile/get
             */
            if($this->url[3] === 'get') {
                $this->_getUserInfo();
            }

            /**
             * url = /api/user/profile/id
             */
            if($this->url[3] === 'id') {
                $this->_getCurrentUserId();
            }

            /**
             * url = /api/user/profile/social-networks/
             */
            if($this->url[3] === 'social-networks') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/user/profile/social-networks/get
                     */
                    if($this->url[4] === 'get') {
                        $this->_getUserSocialNetworks();
                    }

                    /**
                     * url = /api/user/profile/social-networks/edit
                     */
                    if($this->url[4] === 'edit') {
                        $this->_editUserSocialNetworks();
                    }
                }
            }

            /**
             * url = /api/user/profile/personal-info/
             */
            if($this->url[3] === 'personal-info') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/user/profile/personal-info/get
                     */
                    if($this->url[4] === 'get') {
                        $this->_getUserPersonalInfo();
                    }

                    /**
                     * url = /api/user/profile/personal-info/edit
                     */
                    if($this->url[4] === 'edit') {
                        $this->_editUserPersonalInfo();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/user/profile/id
     */
    protected function _getCurrentUserId()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = SessionHelper::getUserSession();
            if (!$id) {
                $this->returnJson([
                    'error' => 'Not authorized user!'
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
     *  url = /api/user/order/
     */
    public function order() {
        if (isset($this->url[3])) {
            /**
             * url = /api/user/order/service/
             */
            if($this->url[3] === 'service') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/user/order/service/upcoming/
                     */
                    if($this->url[4] === 'upcoming') {
                        if(isset($this->url[5])) {
                            /**
                             *  url = /api/user/order/service/upcoming/get/
                             */
                            if($this->url[5] === 'get') {
                                if(isset($this->url[6])) {
                                    /**
                                     *  url = /api/user/order/service/upcoming/get/all
                                     */
                                    if($this->url[6] === 'all') {
                                        $this->_getUserComingAppointments();
                                    }
                                }
                            }
                        }
                    }

                    /**
                     * url = /api/user/order/service/cancel
                     */
                    if($this->url[4] === 'cancel') {
                        $this->_cancelServiceOrder();
                    }

                    /**
                     * url = /api/user/order/service/add
                     */
                    if($this->url[4] === 'add') {
                        $this->_orderServiceSchedule();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/user/service/
     */
    public function service() {
        if (isset($this->url[3])) {
            /**
             * url = /api/user/service/get/
             */
            if ($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/user/service/get/workers/
                     */
                    if($this->url[4] === 'workers') {
                        if(isset($this->url[5])) {
                            /**
                             *  url = /api/user/service/get/workers/all
                             */
                            if($this->url[5] === 'all') {
                               $this->_getWorkersForService();
                            }
                        }
                    }

                    /**
                     *  url = /api/user/service/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getServicesAll();
                    }
                }
            }

        }
    }

    /**
     * @return void
     *
     * url = /api/user/worker/get/services/all
     */
    public function worker() {
        if (isset($this->url[3])) {
            /**
             * url = /api/user/worker/get/
             */
            if ($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/user/worker/get/services/
                     */
                    if($this->url[4] === 'services') {
                        if(isset($this->url[5])) {
                            /**
                             *  url = /api/user/worker/get/services/all
                             */
                            if($this->url[5] === 'all') {
                                $this->_getServicesForWorker();
                            }
                        }
                    }

                    if($this->url[4] === 'all') {
                        /**
                         * url = /api/user/worker/get/all
                         */
                        $this->_getWorkersAll();
                    }
                }
            }

        }
    }

    /**
     * @return void
     *
     * url = /api/user/affiliate/
     */
    public function affiliate() {
        if (isset($this->url[3])) {
            /**
             * url = /api/user/affiliate/get/
             */
            if ($this->url[3] === 'get') {
                if(isset($this->url[4])) {

                    if($this->url[4] === 'all') {
                        /**
                         * url = /api/user/affiliate/get/all
                         */
                        $this->_getAffiliatesAll();
                    }
                }
            }

        }
    }

    /**
     * @return void
     *
     * url = /api/user/schedule/
     */
    public function schedule() {
        if (isset($this->url[3])) {
            /**
             * url = /api/user/schedule/search
             */
            if ($this->url[3] === 'search') {
                $this->_searchSchedule();
            }
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
     * url = /api/user/profile/get
     */
    protected function _getUserInfo()
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
            ], 404);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/profile/social-networks/get
     */
    protected function _getUserSocialNetworks()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->_missingRequestFields();
            }
            $userId = htmlspecialchars(trim($_GET['id']));

            /**
             * @var UserSocialReadDto|false $result
             */
            $result = $this->dataMapper->selectUserSocialById($userId);
            if ($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting user's social info"
                ], 404);
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
     * url = /api/user/profile/social-networks/edit
     */
    protected function _editUserSocialNetworks()
    {
        /**
         * {
         *      Instagram
         *      Facebook
         *      TikTok
         *      YouTube
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
                'YouTube' => htmlspecialchars(trim($_POST['YouTube']))
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
             * Update the social networks of the user in database
             */
            $updated = $this->dataMapper->updateUserSocialNetworksById(
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


    /**
     * @return void
     *
     * url = /api/user/profile/personal-info/get
     */
    protected function _getUserPersonalInfo()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->_missingRequestFields();
            }
            $userId = htmlspecialchars(trim($_GET['id']));

            $result = $this->dataMapper->selectUserPersonalInfoById($userId);
            if ($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting user's personal info"
                ], 404);
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
     * url = /api/user/profile/personal-info/edit
     */
    protected function _editUserPersonalInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['id']) || empty($_POST['name'])
                || empty($_POST['surname']) || empty($_POST['email'])) {
                $this->_missingRequestFields();
            }
            $items = [
                'id' => htmlspecialchars(trim($_POST['id'])),
                'name' => htmlspecialchars(trim($_POST['name'])),
                'surname' => htmlspecialchars(trim($_POST['surname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
            ];

            /**
             * Data Validation
             */
            $valid = $this->_validateEditPersonalInfo($items);
            if(isset($valid['error'])) {
                $this->returnJson($valid, 404);
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
             * Update the user personal information in the database
             */
            $this->dataMapper->beginTransaction();

            $newImage = $photoChanged === true
                ? $_FILES['photo']['random_name']
                : null;

            /**
             * Update textual info
             */
            $updatedText = $this->dataMapper->updateUserPersonalInfoById(
                $items['id'], $items['name'], $items['surname'], $items['email']
            );

            /**
             * Update photo
             */
            $updatedPhoto = true;
            if($newImage) {
                /**
                 * Check the old main photo
                 */
                $oldPhotoFilename = $this->dataMapper->selectUserMainPhotoByUserId($items['id']);
                if($oldPhotoFilename === false) {
                    $this->dataMapper->rollBackTransaction();
                    $this->returnJson([
                        'error' => "An error occurred while getting the current user's photo"
                    ], 404);
                }

                /**
                 * Check if new photo differ from the old one
                 */
                if(!$oldPhotoFilename || $newImage !== $oldPhotoFilename) {
                    /**
                     * If yes -> update photo in db to the new one
                     */
                    $updatedPhoto = $this->dataMapper->updateUserMainPhotoByUserId(
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
                    $folderPath = USERS_PHOTO_FOLDER . "user_{$items['id']}/";

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
                            'error' => "An error occurred while deleting the old user's main photo "
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
    private function _validateEditPersonalInfo($items)
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

        return true;
    }

    /**
     * @return void
     *
     * url = /api/user/order/service/upcoming/get/all
     */
    protected function _getUserComingAppointments()
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
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting user's coming appointments"
            ], 404);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/service/get/workers/all
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

    /**
     * @return void
     *
     * url = /api/user/worker/get/all
     */
    protected function _getWorkersAll()
    {
       parent::_getWorkersAll();
    }

    /**
     * @return void
     *
     * url = /api/user/service/get/all
     */
    protected function _getServicesAll()
    {
        parent::_getServicesAll();
    }

    /**
     * @return void
     *
     * url = /api/user/affiliate/get/all
     */
    protected function _getAffiliatesAll()
    {
        parent::_getAffiliatesAll();
    }

    /**
     * @return void
     *
     * url = /api/user/schedule/search
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
     * @throws \Exception
     *
     * url = /api/user/order/service/add
     */
    protected function _orderServiceSchedule() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            /**
             * Get user id to work with
             */
            $userId = SessionHelper::getUserSession();
            if(!$userId) {
                if(!isset($_POST['email'])) {
                    $this->returnJson([
                        'error' => 'No user specified!'
                    ], 400);
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
                ], 404);
            }
            if($scheduleDetails === null) {
                $this->returnJson([
                    'error' => 'There is no schedule with such id'
                ], 404);
            }

            /**
             * Get user email
             */
            if($userId) {
                $email = $this->dataMapper->selectUserEmailById($userId);
                if($email === false) {
                    $this->returnJson([
                        'error' => 'The error occurred while getting user email'
                    ], 404);
                }
                if($email === null) {
                    $this->returnJson([
                        'error' => 'There is no user email for the given id'
                    ], 404);
                }
            }

            /**
             * Check if there is no orders with such schedule_id
             */
            $exists = $this->dataMapper->selectOrderServiceByScheduleId($scheduleId);
            if($exists) {
                $this->returnJson([
                    'error' => 'The order for selected schedule item already exists'
                ], 403);
            }

            /**
             * Insert the new row into order_service
             */
            $price_id = explode('.', WorkersServiceSchedule::$price_id)[1];
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

            /**
             * Check if the current appointment is not overlapping with another service
             * order of the user that want to order the current one
             */
            $isOverlapped = $this->dataMapper->selectScheduleForUserByTimeInterval(
                $email, $start_datetime, $end_datetime
            );
            if($isOverlapped === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting your current schedule!'
                ], 404);
            }
            if($isOverlapped) {
                $this->returnJson([
                    'error' => 'There is an overlapping with another of your appointments! Please, review your schedule for the selected day to choose available time intervals for one more appointment!'
                ], 404);
            }

            $this->dataMapper->beginTransaction();

            $orderID = $this->dataMapper->insertOrderService(
                $scheduleId, $userId, $email, $scheduleDetails[$price_id],
                $scheduleDetails[$affiliate_id], $start_datetime, $end_datetime,
            );
            if($orderID === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'There is error occurred while inserting order into database'
                ], 404);
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
                ], 404);
            }

            /**
             * Get the order details for email letter
             *
             *  {
             *       id:
             *       email:
             *       start_datetime:
             *       price:
             *       currency:
             *       city:
             *       address:
             *       user_name:
             *       user_surname:
             *       worker_id:
             *       worker_name:
             *       worker_surname:
             *       service_name:
             *  }
             */

            $this->dataMapper->commitTransaction();

            $orderDetails = $this->dataMapper->selectOrderDetailsById($orderID);
            if($orderDetails === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the order details.'
                ], 404);
            }

            /**
             * Send the letter to user with order details
             */
            $emailSent = UserEmailHelper::sendLetterToInformUserAboutServiceOrder(
                $email, $orderDetails
            );
            if($emailSent === false) {
                $this->returnJson([
                    'error' => 'An error occurred while sending email with order details!'
                ], 502);
            }

            $this->returnJson([
                'success' => "You successfully created the order for the service",
                'data' => [
                    'schedule_id' => $scheduleId
                ]
            ], 201);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/order/service/cancel
     */
    protected function _cancelServiceOrder() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = htmlspecialchars(trim($_POST['order_id']));

            $this->dataMapper->beginTransaction();

            /**
             * Changed the cancellation time of the service order
             */
            $canceled = $this->dataMapper->updateServiceOrderCanceledDatetimeById($id);
            if($canceled === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating cancellation datetime of the order!'
                ], 404);
            }

            /**
             * Check if the canceled order has been created by choosing
             * available schedule for specific service/worker
             */
            $scheduleId = $this->dataMapper->selectScheduleIdByOrderId($id);
            if($scheduleId === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while getting schedule id'
                ], 404);
            }

            if($scheduleId === null) {
                /**
                 * If not set -> return success message
                 */
                $this->dataMapper->commitTransaction();
                $this->returnJson([
                    'success' => 'You successfully canceled the appointment!'
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
                ], 404);
            }

            /**
             * Return success
             */
            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => 'You successfully canceled the appointment!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
    }
}