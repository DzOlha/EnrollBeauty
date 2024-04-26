<?php

namespace Src\Service\Auth\Admin;

use Src\Helper\Data\AdminDefault;
use Src\Helper\Http\HttpCode;
use Src\Helper\Http\HttpRequest;
use Src\Helper\Provider\Api\Web\WebApiProvider;
use Src\Helper\Session\SessionHelper;
use Src\Helper\Trimmer\impl\RequestTrimmer;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DTO\Write\AdminWriteDTO;
use Src\Model\DTO\Write\UserWriteDto;
use Src\Model\Entity\Roles;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\Worker\WorkerAuthService;
use Src\Service\Hasher\impl\PasswordHasher;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PasswordHashValidator;
use Src\Service\Validator\impl\PasswordValidator;

class AdminAuthService extends WorkerAuthService
{
    public AuthService $authUser;
    public function __construct(
        DataMapper $dataMapper, AuthService $authUser
    ){
        parent::__construct($dataMapper);
        $this->authUser = $authUser;
    }

    public function defaultAdminRegister(): array
    {
        $admin = AdminDefault::getAdminDefault();
        $dbContainsAdmins = $this->dataMapper->selectAllAdminsRows();
        if ($dbContainsAdmins) {
            return [
                'error' => 'The database already contains administrators! The default admin can be created only if there is no admin users in the database!',
                'code' => HttpCode::forbidden()
            ];
        }
        return $this->registerAdmin($admin);
    }

    public function registerAdmin($admin = null)
    {
        if ($admin === null) {
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

                $admin = [
                    'name'             => $request->get('name'),
                    'surname'          => $request->get('surname'),
                    'email'            => $request->get('email'),
                    'password'         => $request->get('password'),
                    'confirm-password' => $request->get('confirm-password'),
                    'role'             => Roles::$ADMIN
                ];
            }
            else {
                return $this->_methodNotAllowed(['POST']);
            }
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
                'error' => 'Name must be at least 3 characters long and contain only letters',
                'code' => HttpCode::unprocessableEntity()
            ];
        }

        /**
         * Surname
         */
        $validSurname = $nameValidator->validate($admin['surname']);
        if (!$validSurname) {
            return [
                'error' => 'Surname must be at least 3 characters long and contain only letters',
                'code' => HttpCode::unprocessableEntity()
            ];
        }

        /**
         * Email
         */
        $validEmail = $emailValidator->validate($admin['email']);
        if (!$validEmail) {
            return [
                'error' => 'Please enter an email address in the format myemail@mailservice.domain',
                'code' => HttpCode::unprocessableEntity()
            ];
        }

        /**
         * Password
         */
        $validPass = $passwordValidator->validate($admin['password']);
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
        if (isset($admin['confirm-password']) && $admin['password'] !== $admin['confirm-password']) {
            return [
                'error' => 'Passwords do not match',
                'code' => HttpCode::unprocessableEntity()
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
                'code' => HttpCode::forbidden(),
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
                'error' => "The error occurred while creating a new admin account!",
                'code' => HttpCode::notFound()
            ];
        }

        /**
         * Insert into 'admin_setting'
         */
        $inserted = $this->dataMapper->insertAdminSetting($adminId);
        if ($inserted === false) {
            $this->dataMapper->rollBackTransaction();
            return [
                'error' => "The error occurred while inserting admin setting!",
                'code' => HttpCode::notFound()
            ];
        }

        $this->dataMapper->commitTransaction();
        return [
            'success' => "You successfully created a new admin account!",
            'code' => HttpCode::created()
        ];
    }

    public function changeDefaultAdminData() {
        if (HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['name']) || !isset($DATA['surname'])
                || !isset($DATA['email']) || !isset($DATA['old-password'])
                || !isset($DATA['new-password']) || !isset($DATA['confirm-new-password']))
            {
                return $this->_missingRequestFields();
            }

            $admin = [
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
                'old-password' => $request->get('old-password'),
                'new-password' => $request->get('new-password'),
                'confirm-new-password' => $request->get('confirm-new-password')
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
                    'error' => 'The database does not contain any administrators!',
                    'code' => HttpCode::notFound()
                ];
            }
            if ($countAdmins > 1) {
                return [
                    'error' => 'The database contains more than one administrator!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Name
             */
            $validName = $nameValidator->validate($admin['name']);
            if (!$validName) {
                return [
                    'error' => 'Name must be at least 3 characters long and contain only letters',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Surname
             */
            $validSurname = $nameValidator->validate($admin['surname']);
            if (!$validSurname) {
                return [
                    'error' => 'Surname must be at least 3 characters long and contain only letters',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Email
             */
            $validEmail = $emailValidator->validate($admin['email']);
            if (!$validEmail) {
                return [
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Check if the admin with such email is already registered
             */
            $registeredBefore = $this->dataMapper->selectAdminIdByEmail($admin['email']);
            if ($registeredBefore) {
                return [
                    'error' => "The admin with such email is already registered!",
                    'code' => HttpCode::forbidden()
                ];
            }

            /**
             * Old Password -> format validation
             */
            $validPass = $passwordValidator->validate($admin['old-password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }
            /**
             * Old Password -> equation with the real default password validation
             */
            $defaultPassword = AdminDefault::getAdminDefaultPassword();
            if($defaultPassword !== $admin['old-password']) {
                return [
                    'error' => 'Incorrect old password provided!',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * New Password
             */
            $validPass = $passwordValidator->validate($admin['new-password']);
            if (!$validPass) {
                return [
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, 
                                one digit, one special character, and be between 8 to 30 characters long',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Check if new password differs from the default one
             */
            if($admin['new-password'] === $defaultPassword) {
                return [
                    'error' => 'Please provide correct new password!',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Confirm New Password
             */
            if ($admin['confirm-new-password']
                && $admin['new-password'] !== $admin['confirm-new-password']) {
                return [
                    'error' => 'New password and its confirmation do not match',
                    'code' => HttpCode::unprocessableEntity()
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
                    'error' => 'The error occurred while updating the admin information!',
                    'code' => HttpCode::notFound()
                ];
            }
            return [
                'success' => 'You successfully changed admin details!'
            ];
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }

    public function loginAdmin() {
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
                    'error' => 'Please enter an email address in the format myemail@mailservice.domain',
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
            $actualPasswordHash = $this->dataMapper->selectAdminPasswordByEmail($items['email']);
            if ($actualPasswordHash === false) {
                return [
                    'error' => 'There is no admin with such email',
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
                    'error' => 'The provided password does not match the one saved for the requested admin!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Select User ID for storing it into session
             */
            $adminId = $this->dataMapper->selectAdminIdByEmail($items['email']);
            if ($adminId === false) {
                return [
                    'error' => 'The error occurred while getting user id!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Select admin status
             */
            $status = $this->dataMapper->selectAdminStatus($adminId);
            if($status === 0) {
                return [
                    'error' => 'The requested Admin account is inactive. Please, change the default credentials to yours to be able to login.',
                    'code' => HttpCode::forbidden()
                ];
            }

            /**
             * Redirect the user to the requested page (if such was attempted to be accessed)
             */
            $rememberUrl = SessionHelper::getRememberUrlSession('admin');
            $redirectUrl = $rememberUrl !== false ? $rememberUrl : WebApiProvider::adminHome();
            SessionHelper::removeAllRememberUrlSession();

            /**
             * Store Admin's ID into session
             */
            SessionHelper::setAdminSession($adminId);
            return [
                'success' => true,
                'data' => [
                    'id' => SessionHelper::getAdminSession(),
                    'redirect_url' => $redirectUrl
                ]
            ];
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }

}