<?php

namespace Src\Service\Auth\User;

use Src\Helper\Email\UserEmailHelper;
use Src\Helper\Http\HttpCode;
use Src\Helper\Http\HttpRequest;
use Src\Helper\Provider\Api\Web\WebApiProvider;
use Src\Helper\Session\SessionHelper;
use Src\Helper\Trimmer\impl\RequestTrimmer;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DTO\Write\UserWriteDto;
use Src\Service\Auth\AuthService;
use Src\Service\Hasher\impl\PasswordHasher;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PasswordHashValidator;
use Src\Service\Validator\impl\PasswordValidator;

class UserAuthService extends AuthService
{
    /**
     * @param DataMapper $dataMapper
     */
    public function __construct(DataMapper $dataMapper)
    {
        parent::__construct($dataMapper);
    }

    public function registerUser()
    {
        if (HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['name']) || !isset($DATA['surname'])
            || !isset($DATA['email']) || !isset($DATA['password'])
            || !isset($DATA['confirm-password']))
            {
                return $this->_missingRequestFields();
            }

            $items = [
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'confirm-password' => $request->get('confirm-password')
            ];

            $nameValidator = new NameValidator();
            $emailValidator = new EmailValidator();
            $passwordValidator = new PasswordValidator();

            /**
             * Name
             */
            $validName = $nameValidator->validate($items['name']);
            if (!$validName) {
                return [
                    'error' => 'Name must be between 3-50 characters long and contain only letters with dashes',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Surname
             */
            $validSurname = $nameValidator->validate($items['surname']);
            if (!$validSurname) {
                return [
                    'error' => 'Surname must be between 3-50 characters long and contain only letters with dashes',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($items['email']);
            if (!$validEmail) {
                return [
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain that not exceeds 100 characters',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Confirm Password
             */
            if ($items['password'] !== $items['confirm-password']) {
                return [
                    'error' => 'Passwords do not match',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            $passwordHash = PasswordHasher::hash($items['password']);

            /**
             * Check if the user with such email is already registered
             */
            $registeredBefore = $this->dataMapper->selectUserIdByEmail($items['email']);
            if ($registeredBefore) {
                return [
                    'error' => "The user with such email is already registered!",
                    'code' => HttpCode::forbidden()
                ];
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
                return [
                    'error' => "The error occurred while creating a new user account!",
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Insert into 'user_setting'
             */
            $inserted = $this->dataMapper->insertNewUserSetting($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => "The error occurred while inserting user setting!",
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Insert into 'user_photo'
             */
            $mainPhoto = 1;
            $inserted = $this->dataMapper->insertNewUserPhoto($userId, $mainPhoto);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => "The error occurred while inserting user photo!",
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Insert into 'user_social'
             */
            $inserted = $this->dataMapper->insertNewUserSocial($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => "The error occurred while inserting user social!",
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Sent email with welcoming message
             */
            $emailSent = UserEmailHelper::sendLetterToWelcomeUser(
                $items['email'], $items['name'], $items['surname']
            );
            if($emailSent === false) {
                return [
                    'error' => 'An error occurred while sending welcome email to the user!',
                    'code' => HttpCode::badGateway()
                ];
            }

            $this->dataMapper->commitTransaction();
            return[
                'success' => "You successfully created a new user account!",
                'code' => HttpCode::created()
            ];
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }

    public function loginUser() {
        if (HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['email']) || !isset($DATA['password']))
            {
                return $this->_missingRequestFields();
            }

            $items = [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ];
            $emailValidator = new EmailValidator();
            $passwordValidator = new PasswordValidator();

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($items['email']);
            if (!$validEmail) {
                return [
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain that not exceeds 100 characters',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Get actual password hash
             */
            $actualPasswordHash = $this->dataMapper->selectUserPasswordByEmail($items['email']);
            if ($actualPasswordHash === false) {
                return [
                    'error' => 'There is no user with such email',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Check Password Equality
             */
            $hashValidator = new PasswordHashValidator($actualPasswordHash);
            $validPassword = $hashValidator->validate($items['password']);
            if (!$validPassword) {
                return [
                    'error' => 'The provided password does not match the one saved for the requested user!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Select User ID for storing it into session
             */
            $userId = $this->dataMapper->selectUserIdByEmail($items['email']);
            if ($userId === false) {
                return [
                    'error' => 'The error occurred while getting user id!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Redirect the user to the requested page (if such was attempted to be accessed)
             */
            $rememberUrl = SessionHelper::getRememberUrlSession('user');
            $redirectUrl = $rememberUrl !== false ? $rememberUrl : WebApiProvider::userHome();
            SessionHelper::removeAllRememberUrlSession();

            /**
             * Store User's ID into session
             */
            SessionHelper::setUserSession($userId);
            return [
                'success' => true,
                'data' => [
                    'id' => SessionHelper::getUserSession(),
                    'redirect_url' => $redirectUrl
                ]
            ];
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }
}