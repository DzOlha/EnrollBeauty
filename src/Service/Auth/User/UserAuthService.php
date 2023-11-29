<?php

namespace Src\Service\Auth\User;

use Src\Helper\Session\SessionHelper;
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
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ];
            }

            /**
             * Confirm Password
             */
            if ($items['password'] !== $items['confirm-password']) {
                return [
                    'error' => 'Passwords do not match'
                ];
            }

            $passwordHash = PasswordHasher::hash($items['password']);

            /**
             * Check if the user with such email is already registered
             */
            $registeredBefore = $this->dataMapper->selectUserIdByEmail($items['email']);
            if ($registeredBefore) {
                return [
                    'error' => "The user with such email is already registered!"
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
                    'error' => "The error occurred while creating a new user account!"
                ];
            }

            /**
             * Insert into 'user_setting'
             */
            $inserted = $this->dataMapper->insertNewUserSetting($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => "The error occurred while inserting user setting!"
                ];
            }

            /**
             * Insert into 'user_photo'
             */
            $inserted = $this->dataMapper->insertNewUserPhoto($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => "The error occurred while inserting user photo!"
                ];
            }

            /**
             * Insert into 'user_social'
             */
            $inserted = $this->dataMapper->insertNewUserSocial($userId);
            if ($inserted === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => "The error occurred while inserting user social!"
                ];
            }

            $this->dataMapper->commitTransaction();
            return[
                'success' => "You successfully created a new user account!"
            ];
        }
        return [];
    }

    public function loginUser() {
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
                return [
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain'
                ];
            }

            /**
             * Password
             */
            $validPass = $passwordValidator->validate($items['password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ];
            }

            /**
             * Get actual password hash
             */
            $actualPasswordHash = $this->dataMapper->selectUserPasswordByEmail($items['email']);
            if ($actualPasswordHash === false) {
                return [
                    'error' => 'There is no user with such email'
                ];
            }

            /**
             * Check Password Equality
             */
            $hashValidator = new PasswordHashValidator($actualPasswordHash);
            $validPassword = $hashValidator->validate($items['password']);
            if (!$validPassword) {
                return [
                    'error' => 'The provided password does not match the one saved for the requested user!'
                ];
            }

            /**
             * Select User ID for storing it into session
             */
            $userId = $this->dataMapper->selectUserIdByEmail($items['email']);
            if ($userId === false) {
                return [
                    'error' => 'The error occurred while getting user id!'
                ];
            }

            /**
             * Store User's ID into session
             */
            SessionHelper::setUserSession($userId);
            return [
                'success' => true,
                'data' => [
                    'session' => SessionHelper::getUserSession()
                ]
            ];
        }
        return [];
    }
}