<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataSource\extends\UserDataSource;
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
    public function register() {
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
            if(!$validName) {
                $this->returnJson([
                    'error' => 'Name must be at least 3 characters long and contain only letters'
                ]);
            }

            /**
             * Surname
             */
            $validSurname = $nameValidator->validate($items['surname']);
            if(!$validSurname) {
                $this->returnJson([
                    'error' => 'Surname must be at least 3 characters long and contain only letters'
                ]);
            }

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($items['email']);
            if(!$validEmail) {
                $this->returnJson([
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain'
                ]);
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if(!$validPass) {
                $this->returnJson([
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ]);
            }

            /**
             * Confirm Password
             */
            if($items['password'] !== $items['confirm-password']) {
                $this->returnJson([
                    'error' => 'Passwords do not match'
                ]);
            }

            $passwordHash = PasswordHasher::hash($items['password']);

            /**
             * Check if the user with such email is already registered
             */
            $registeredBefore = $this->dataMapper->selectUserIdByEmail($items['email']);
            if($registeredBefore) {
                $this->returnJson([
                    'error' => "The user with such email is already registered!"
                ]);
            }

            $this->dataMapper->beginTransaction();
            /**
             * Insert into 'users' table
             */
            $userId = $this->dataMapper->insertNewUser(
                $items['name'], $items['surname'], $items['email'], $passwordHash
            );
            if($userId === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while creating a new user account!"
                ]);
            }

            /**
             * Insert into 'user_setting'
             */
            $inserted = $this->dataMapper->insertNewUserSetting($userId);
            if($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while inserting user setting!"
                ]);
            }

            /**
             * Insert into 'user_photo'
             */
            $inserted = $this->dataMapper->insertNewUserPhoto($userId);
            if($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => "The error occurred while inserting user photo!"
                ]);
            }

            /**
             * Insert into 'user_social'
             */
            $inserted = $this->dataMapper->insertNewUserSocial($userId);
            if($inserted === false) {
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
    public function login() {
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
            if(!$validEmail) {
                $this->returnJson([
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain'
                ]);
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if(!$validPass) {
                $this->returnJson([
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ]);
            }

            /**
             * Get actual password hash
             */
            $actualPasswordHash = $this->dataMapper->selectUserPasswordByEmail($items['email']);
            if($actualPasswordHash === false) {
                $this->returnJson([
                    'error' => 'There is no user with such email'
                ]);
            }

            /**
             * Check Password Equality
             */
            $hashValidator = new PasswordHashValidator($actualPasswordHash);
            $validPassword = $hashValidator->validate($items['password']);
            if(!$validPassword) {
                $this->returnJson([
                    'error' => 'The provided password does not match the one saved for the requested user!'
                ]);
            }

            /**
             * Select User ID for storing it into session
             */
            $userId = $this->dataMapper->selectUserIdByEmail($items['email']);
            if($userId === false) {
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
    private function _getUserId() {
        $userId = 0;
        if(isset($_GET['user_id']) && $_GET['user_id'] !== '') {
            $userId = htmlspecialchars(trim($_GET['user_id']));
        } else {
            if(isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
            }
        }
        return $userId;
    }

    /**
     * @return void
     *
     * url = /api/user/getUserInfo
     */
    public function getUserInfo() {
        $userId = $this->_getUserId();
        $result = $this->dataMapper->selectUserInfoById($userId);
        if($result) {
            $this->returnJson([
                'success' => true,
                'user' => $result
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
    public function getUserSocialNetworks() {
        $userId = $this->_getUserId();
        $result = $this->dataMapper->selectUserSocialById($userId);
        if($result) {
            $this->returnJson([
                'success' => true,
                'user' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting user's social info"
            ]);
        }
    }

}