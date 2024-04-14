<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Email\UserEmailHelper;
use Src\Helper\Http\HttpCode;
use Src\Helper\Http\HttpRequest;
use Src\Helper\Provider\Folder\FolderProvider;
use Src\Helper\Session\SessionHelper;
use Src\Helper\Trimmer\impl\RequestTrimmer;
use Src\Helper\Uploader\impl\FileUploader;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Model\DTO\Read\UserReadDto;
use Src\Model\DTO\Read\UserSocialReadDto;
use Src\Model\Table\WorkersServiceSchedule;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
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

    public function checkPermission(array $url = []): void
    {
        if(!SessionHelper::getUserSession()) {
            $this->_accessDenied(
                'Access is denied to the requested resource! Please, log in as a User to be able to perform the action!'
            );
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
        if (HttpRequest::method() === 'GET')
        {
            $id = SessionHelper::getUserSession();
            if (!$id) {
                $this->_notAuthorizedUser();
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
            ], HttpCode::notFound());
        }
    }

    /**
     * @return void
     *
     * url = /api/user/profile/social-networks/get
     */
    protected function _getUserSocialNetworks()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }
            $userId = $request->get('id');

            /**
             * @var UserSocialReadDto|false $result
             */
            $result = $this->dataMapper->selectUserSocialById($userId);
            if ($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting user's social info"
                ], HttpCode::notFound());
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
        if(HttpRequest::method() === 'PUT')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }
            $rowId = $request->get('id');

            $items = [
                'Instagram' => $request->get('Instagram'),
                'Facebook' => $request->get('Facebook'),
                'TikTok' => $request->get('TikTok'),
                'YouTube' => $request->get('YouTube')
            ];

            /**
             * Validate all urls
             */
            $validator = new SocialNetworksUrlValidator();
            $valid = $validator->validateAll($items);
            if($valid !== true) {
                $this->returnJson([
                    'error' => $valid
                ], HttpCode::unprocessableEntity());
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
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => 'You successfully updated your social networks!',
                'data' => $items
            ]);
        }
        else {
            $this->_methodNotAllowed(['PUT']);
        }
    }


    /**
     * @return void
     *
     * url = /api/user/profile/personal-info/get
     */
    protected function _getUserPersonalInfo()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }
            $userId = $request->get('id');

            $result = $this->dataMapper->selectUserPersonalInfoById($userId);
            if ($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting user's personal info"
                ], HttpCode::notFound());
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
        if (HttpRequest::method() === 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if (empty($DATA['id']) || empty($DATA['name'])
                || empty($DATA['surname']) || empty($DATA['email'])) {
                $this->_missingRequestFields();
            }

            $items = [
                'id' => $request->get('id'),
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
            ];

            /**
             * Data Validation
             */
            $valid = $this->_validateEditPersonalInfo($items);
            if(isset($valid['error'])) {
                $this->returnJson($valid, HttpCode::notFound());
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
                    $this->returnJson($validPhoto, HttpCode::unprocessableEntity());
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
                    ], HttpCode::notFound());
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
                        ], HttpCode::notFound());
                    }

                    /**
                     * Upload the new image into the folder
                     */
                    $uploader = new FileUploader();
                    $folderPath = FolderProvider::userUploadsImg($items['id']);

                    $uploaded = $uploader->upload(
                        $_FILES['photo'], $_FILES['photo']['random_name'], $folderPath
                    );
                    if(!$uploaded) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => 'An error occurred while uploading your main photo into appropriate folder!'
                        ], HttpCode::notFound());
                    }

                    /**
                     * Remove the old photo from the folder
                     */
                    $deleted = FileUploader::deleteFileFromFolder($folderPath, $oldPhotoFilename);
                    if($deleted === false) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => "An error occurred while deleting the old user's main photo "
                        ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
            ], HttpCode::notFound());
        }
    }

    /**
     * @return void
     *
     * url = /api/user/service/get/workers/all
     */
    protected function _getWorkersForService()
    {
        parent::_getWorkersForService();
    }

    /**
     * @return void
     *
     * url = /api/user/worker/get/services/all
     */
    protected function _getServicesForWorker()
    {
        parent::_getServicesForWorker();
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
        parent::_searchSchedule();
    }

    /**
     * @return void
     * @throws \Exception
     *
     * url = /api/user/order/service/add
     */
    protected function _orderServiceSchedule()
    {
        if(HttpRequest::method() === 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            /**
             * Get user id to work with
             */
            $userId = SessionHelper::getUserSession();
            if(!$userId) {
                if(!isset($DATA['email'])) {
                    $this->_notAuthorizedUser(
                        'To be able to make an order, please, register or log into your account!'
                    );
                } else {
                    $userId = null;
                    $email = $request->get('email');
                }
            }

            $scheduleId = $request->get('schedule_id');

            /**
             * Get schedule details
             */
            $scheduleDetails = $this->dataMapper->selectWorkerScheduleItemById($scheduleId);
            if($scheduleDetails === false) {
                $this->returnJson([
                    'error' => 'There is error occurred while getting schedule details'
                ], HttpCode::notFound());
            }
            if($scheduleDetails === null) {
                $this->returnJson([
                    'error' => 'There is no schedule with such id'
                ], HttpCode::notFound());
            }

            /**
             * Get user email
             */
            if($userId) {
                $email = $this->dataMapper->selectUserEmailById($userId);
                if($email === false) {
                    $this->returnJson([
                        'error' => 'The error occurred while getting user email'
                    ], HttpCode::notFound());
                }
                if($email === null) {
                    $this->returnJson([
                        'error' => 'There is no user email for the given id'
                    ], HttpCode::notFound());
                }
            }

            /**
             * Check if there is no orders with such schedule_id
             */
            $exists = $this->dataMapper->selectOrderServiceByScheduleId($scheduleId);
            if($exists) {
                $this->returnJson([
                    'error' => 'The order for selected schedule item already exists'
                ], HttpCode::forbidden());
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
                ], HttpCode::notFound());
            }
            if($isOverlapped) {
                $this->returnJson([
                    'error' => 'There is an overlapping with another of your appointments! Please, review your schedule for the selected day to choose available time intervals for one more appointment!'
                ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
                ], HttpCode::badGateway());
            }

            $this->returnJson([
                'success' => "You successfully created the order for the service",
                'data' => [
                    'schedule_id' => $scheduleId
                ]
            ], HttpCode::created());
        }
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }

    /**
     * @return void
     *
     * url = /api/user/order/service/cancel
     */
    protected function _cancelServiceOrder()
    {
        if(HttpRequest::method() === 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(!isset($DATA['order_id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('order_id');

            $this->dataMapper->beginTransaction();

            /**
             * Changed the cancellation time of the service order
             */
            $canceled = $this->dataMapper->updateServiceOrderCanceledDatetimeById($id);
            if($canceled === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'The error occurred while updating cancellation datetime of the order!'
                ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
                ], HttpCode::notFound());
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
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }
}