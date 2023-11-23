<?php

namespace Src\Service\Auth\Admin;

use Src\Helper\Data\AdminDefault;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\DTO\Write\UserWriteDto;
use Src\Model\Entity\Roles;
use Src\Service\Auth\AuthService;
use Src\Service\Hasher\impl\PasswordHasher;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PasswordHashValidator;
use Src\Service\Validator\impl\PasswordValidator;

class AdminAuthService extends AuthService
{
    public function __construct(DataMapper $dataMapper)
    {
        parent::__construct($dataMapper);
    }

    public function defaultAdminRegister(): array
    {
        $admin = AdminDefault::getAdminDefault();
        $dbContainsAdmins = $this->dataMapper->selectAllAdminsRows();
        if ($dbContainsAdmins) {
            return [
                'error' => 'The database already contains administrators! The default admin can be created only if there is no admin users in the database!'
            ];
        }
        return $this->register($admin);
    }

    public function register($admin = null)
    {
        if ($admin === null) {
            $admin = [
                'name' => htmlspecialchars($_POST['name']),
                'surname' => htmlspecialchars($_POST['surname']),
                'email' => htmlspecialchars($_POST['email']),
                'password' => htmlspecialchars($_POST['password']),
                'confirm-password' => htmlspecialchars(trim($_POST['confirm-password'] ?? '')),
                'role' => Roles::$ADMIN
            ];
        }
        $nameValidator = new NameValidator();
        $emailValidator = new EmailValidator();
        $passwordValidator = new PasswordValidator();

        /**
         * Name
         */
        $validName = $nameValidator->validate($admin['name']);
        if (!$validName) {
            return [
                'error' => 'Name must be at least 3 characters long and contain only letters'
            ];
        }

        /**
         * Surname
         */
        $validSurname = $nameValidator->validate($admin['surname']);
        if (!$validSurname) {
            return [
                'error' => 'Surname must be at least 3 characters long and contain only letters'
            ];
        }

        /**
         * Email
         */
        $validEmail = $emailValidator->validate($admin['email']);
        if (!$validEmail) {
            return [
                'error' => 'Please enter an email address in the format myemail@mailservice.domain'
            ];
        }

        /**
         * Password
         */
        $validPass = $passwordValidator->validate($admin['password']);
        if (!$validPass) {
            return [
                'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
            ];
        }

        /**
         * Confirm Password
         */
        if (isset($admin['confirm-password']) && $admin['password'] !== $admin['confirm-password']) {
            return [
                'error' => 'Passwords do not match'
            ];
        }

        $passwordHash = PasswordHasher::hash($admin['password']);

        /**
         * Check if the admin with such email is already registered
         */
        $registeredBefore = $this->dataMapper->selectAdminIdByEmail($admin['email']);
        if ($registeredBefore) {
            $defaultAlreadyRegistered = false;
            if($admin['email'] === AdminDefault::getAdminDefaultEmail()) {
                $defaultAlreadyRegistered = true;
            }
            return [
                'error' => "The admin with such email is already registered!",
                'default_already_registered' => $defaultAlreadyRegistered
            ];
        }

        $this->dataMapper->beginTransaction();

        $adminObj = new AdminWriteDTO(
            $admin['name'], $admin['surname'], $admin['email'],
            $passwordHash, $admin['role'] ?? null
        );

        $adminId = $this->dataMapper->insertAdmin($adminObj);
        if ($adminId === false) {
            $this->dataMapper->rollBackTransaction();
            return [
                'error' => "The error occurred while creating a new admin account!"
            ];
        }

        /**
         * Insert into 'admin_setting'
         */
        $inserted = $this->dataMapper->insertAdminSetting($adminId);
        if ($inserted === false) {
            $this->dataMapper->rollBackTransaction();
            return [
                'error' => "The error occurred while inserting admin setting!"
            ];
        }

        $this->dataMapper->commitTransaction();
        return [
            'success' => "You successfully created a new admin account!"
        ];
    }

    public function changeDefaultAdminData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin = [
                'name' => htmlspecialchars(trim($_POST['name'])),
                'surname' => htmlspecialchars(trim($_POST['surname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'old-password' => htmlspecialchars(trim($_POST['old-password'])),
                'new-password' => htmlspecialchars(trim($_POST['new-password'])),
                'confirm-new-password' => htmlspecialchars(trim($_POST['confirm-new-password']))
            ];
            $nameValidator = new NameValidator();
            $emailValidator = new EmailValidator();
            $passwordValidator = new PasswordValidator();

            /**
             * Check if any admins have been already registered
             */
            $countAdmins = $this->dataMapper->selectAllAdminsRows();
            if (!$countAdmins) {
                return [
                    'error' => 'The database does not contain any administrators!'
                ];
            }
            if ($countAdmins > 1) {
                return [
                    'error' => 'The database contains more than one administrator!'
                ];
            }

            /**
             * Name
             */
            $validName = $nameValidator->validate($admin['name']);
            if (!$validName) {
                return [
                    'error' => 'Name must be at least 3 characters long and contain only letters'
                ];
            }

            /**
             * Surname
             */
            $validSurname = $nameValidator->validate($admin['surname']);
            if (!$validSurname) {
                return [
                    'error' => 'Surname must be at least 3 characters long and contain only letters'
                ];
            }

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($admin['email']);
            if (!$validEmail) {
                return [
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain'
                ];
            }

            /**
             * Check if the admin with such email is already registered
             */
            $registeredBefore = $this->dataMapper->selectAdminIdByEmail($admin['email']);
            if ($registeredBefore) {
                return [
                    'error' => "The admin with such email is already registered!",
                ];
            }

            /**
             * Old Password -> format validation
             */
            $validPass = $passwordValidator->validate($admin['old-password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ];
            }
            /**
             * Old Password -> equation with the real default password validation
             */
            $defaultPassword = AdminDefault::getAdminDefaultPassword();
            if($defaultPassword !== $admin['old-password']) {
                return [
                    'error' => 'Incorrect old password provided!'
                ];
            }

            /**
             * New Password
             */
            $validPass = $passwordValidator->validate($admin['new-password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long'
                ];
            }

            /**
             * Check if new password differs from the default one
             */
            if($admin['new-password'] === $defaultPassword) {
                return [
                    'error' => 'Please provide correct new password!'
                ];
            }

            /**
             * Confirm New Password
             */
            if ($admin['confirm-new-password']
                && $admin['new-password'] !== $admin['confirm-new-password']) {
                return [
                    'error' => 'New password and its confirmation do not match'
                ];
            }

            $newPasswordHash = PasswordHasher::hash($admin['new-password']);

            $makeActiveStatus = 1;
            $adminObj = new AdminWriteDTO(
                $admin['name'], $admin['surname'], $admin['email'],
                $newPasswordHash, Roles::$ADMIN, $makeActiveStatus
            );

            $updated = $this->dataMapper->updateAdmin($adminObj);
            if($updated === false) {
                return [
                    'error' => 'The error occurred while updating the admin information!'
                ];
            }
            return [
                'success' => 'You successfully changed admin details!'
            ];
        }
        return [];
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
            $actualPasswordHash = $this->dataMapper->selectAdminPasswordByEmail($items['email']);
            if ($actualPasswordHash === false) {
                return [
                    'error' => 'There is no admin with such email'
                ];
            }

            /**
             * Check Password Equality
             */
            $hashValidator = new PasswordHashValidator($actualPasswordHash);
            $validPassword = $hashValidator->validate($items['password']);
            if (!$validPassword) {
                return [
                    'error' => 'The provided password does not match the one saved for the requested admin!'
                ];
            }

            /**
             * Select User ID for storing it into session
             */
            $adminId = $this->dataMapper->selectAdminIdByEmail($items['email']);
            if ($adminId === false) {
                return [
                    'error' => 'The error occurred while getting user id!'
                ];
            }

            /**
             * Store Users ID into session
             */
            SessionHelper::setAdminSession($adminId);
            return [
                'success' => true,
                'data' => [
                    'session' => SessionHelper::getAdminSession()
                ]
            ];
        }
        return [];
    }

    public function logout() {

    }
}