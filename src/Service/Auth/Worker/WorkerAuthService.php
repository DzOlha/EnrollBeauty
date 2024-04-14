<?php

namespace Src\Service\Auth\Worker;

use Src\Helper\Email\WorkerEmailHelper;
use Src\Helper\Http\HttpCode;
use Src\Helper\Http\HttpRequest;
use Src\Helper\Provider\Api\Web\WebApiProvider;
use Src\Helper\Session\SessionHelper;
use Src\Helper\Trimmer\impl\RequestTrimmer;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DTO\Write\WorkerWriteDTO;
use Src\Model\Entity\Gender;
use Src\Service\Auth\AuthService;
use Src\Service\Generator\impl\PasswordGenerator;
use Src\Service\Generator\impl\RecoveryCodeGenerator;
use Src\Service\Hasher\impl\PasswordHasher;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PasswordHashValidator;
use Src\Service\Validator\impl\PasswordValidator;
use Src\Service\Validator\impl\RecoveryCodeValidator;

class WorkerAuthService extends AuthService
{
    public function __construct(DataMapper $dataMapper)
    {
        parent::__construct($dataMapper);
    }

    /**
     * @return array
     *
     * POST => {
            name:
            surname:
            email:
            position_id:
            role_id:
            gender:
            age:
            experience:
            salary:
        }
     */
    public function registerWorker(): array
    {
        if (HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['name']) || !isset($DATA['surname'])
                || !isset($DATA['email']) || !isset($DATA['position_id'])
                || !isset($DATA['role_id']) || !isset($DATA['age'])
                || !isset($DATA['experience']))
            {
                return $this->_missingRequestFields();
            }


            $items = [
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
                'position_id' => $request->get('position_id'),
                'role_id' => $request->get('role_id'),
                'gender' => $request->get('gender'),
                'age' => $request->get('age'),
                'experience' => $request->get('experience'),
                'salary' => $request->get('salary'),
            ];
            $nameValidator = new NameValidator();
            $emailValidator = new EmailValidator();

            /**
             * Name
             */
            $validName = $nameValidator->validate($items['name']);
            if (!$validName) {
                return [
                    'error' => 'Name must be at least 3 characters long and contain only letters',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Surname
             */
            $validSurname = $nameValidator->validate($items['surname']);
            if (!$validSurname) {
                return [
                    'error' => 'Surname must be at least 3 characters long and contain only letters',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

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
             * Position id
             */
            if(!$items['position_id']) {
                return [
                    'error' => 'Position is required field!'
                ];
            }
            if(!is_int((int)$items['position_id'])) {
                return [
                    'error' => 'Invalid position has been selected!',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Role id
             */
            if(!$items['role_id']) {
                return [
                    'error' => 'Role is required field!'
                ];
            }
            if(!is_int((int)$items['role_id'])) {
                return [
                    'error' => 'Invalid role has been selected!',
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Gender
             */
            if($items['gender']) {
                if(
                    $items['gender'] !== Gender::$MALE
                    && $items['gender'] !== Gender::$FEMALE
                    && $items['gender'] !== Gender::$OTHER
                ) {
                    return [
                        'error' => 'Invalid gender selected! It should be Male, Female, or Other',
                        'code' => HttpCode::unprocessableEntity()
                    ];
                }
            } else {
                $items['gender'] = null;
            }

            /**
             * Age
             */
            if(!$items['age']) {
                return [
                    'error' => 'Age is required field!'
                ];
            }
            if($items['age'] < 14 || $items['age'] > 80) {
                return [
                    'error' => "The worker's age should be from 14 to 80 years!",
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Years of experience
             */
            if(!$items['experience']) {
                return [
                    'error' => "Years of worker's experience is required field!",
                    'code' => HttpCode::unprocessableEntity()
                ];
            }
            if($items['experience'] < 0 || $items['experience'] > 66) {
                return [
                    'error' => "The years of the worker's experience should be from 0 to 66 years!",
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            /**
             * Salary
             */
            if($items['salary']) {
                if($items['salary'] < 0){
                    return [
                        'error' => 'Salary can not be negative number!',
                        'code' => HttpCode::unprocessableEntity()
                    ];
                }
                if(!is_int((int)$items['salary']) && !is_double((double)$items['salary'])) {
                    return [
                        'error' => 'Invalid salary number is provided!',
                        'code' => HttpCode::unprocessableEntity()
                    ];
                }
            } else {
                $items['salary'] = null;
            }

            /**
             * Check if there is already registered worker with such email
             */
            $existId = $this->dataMapper->selectWorkerByEmail($items['email']);
            if($existId) {
                return [
                    'error' => 'The worker with such email has already been registered!',
                    'code' => HttpCode::forbidden()
                ];
            }

            /**
             * Generate the password for the worker by the regex
             */
            $passGenerator = new PasswordGenerator();
            $password = $passGenerator->generate();
            $passwordHash = PasswordHasher::hash($password);

            /**
             * Generate the recovery code for changing the password
             * of the worker by the link sent to their email
             * with such recovery code
             */
            $recoveryGenerator = new RecoveryCodeGenerator();
            $recoveryCode = $recoveryGenerator->generate();

            $this->dataMapper->beginTransaction();
            /**
             * Insert worker
             */
            $workerObj = new WorkerWriteDTO(
                $items['name'], $items['surname'], $passwordHash, $items['email'],
                $items['gender'], $items['age'], $items['experience'],
                $items['position_id'], $items['salary'], $items['role_id']
            );
            $workerId = $this->dataMapper->insertWorker($workerObj);
            if($workerId === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => 'An error occurred while inserting the worker into database!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Insert worker settings
             */
            $workerSettingId = $this->dataMapper->insertWorkerSettings(
                $workerId, $recoveryCode
            );
            if($workerSettingId === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => 'An error occurred while inserting the worker settings into database!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Insert worker social
             */
            $workerSocial = $this->dataMapper->insertWorkerSocial($workerId);
            if($workerSocial === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => 'An error occurred while inserting the worker social into database!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Insert worker main photo
             */
            $isMain = 1;
            $workerPhoto = $this->dataMapper->insertWorkerPhoto($workerId, $isMain);
            if($workerPhoto === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => 'An error occurred while inserting the worker photo into database!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Send email with url for changing password
             */
            $emailSent = $this->_sendLetterToWelcomeWorker(
                $items['email'], $workerSettingId, $recoveryCode,
                $items['name'], $items['surname']
            );
            if(isset($emailSent['error'])) {
                return $emailSent;
            } else {
                $this->dataMapper->commitTransaction();
                return [
                    'success' => "You successfully registered the worker '{$items['name']} {$items['surname']}'! The letter with the link to get access to the account has been sent to their email.",
                    'code' => HttpCode::created()
                ];
            }
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }

    protected function _sendLetterToWelcomeWorker(
        $email, $workerSettingId, $recoveryCode, $name, $surname
    ) {
        $emailSent = WorkerEmailHelper::sendLetterToWelcomeWorker(
            $email, $recoveryCode, $name, $surname
        );

        if ($emailSent === true) {
            $success = $this->dataMapper->updateWorkerSettingDateOfSendingRecoveryCode(
                $workerSettingId, $recoveryCode
            );
            if ($success) {
                return [
                    'success' => true
                ];
            } else {
                return [
                    'error' => 'Something went wrong while saving password recovery code!',
                    'code' => HttpCode::notFound()
                ];
            }
        } else {
            return [
                'error' => $emailSent,
                'code' => HttpCode::badGateway()
            ];
        }
    }


    /**
     * @return array
     *
     * used by Web controller, so return 'title' too
     */
    public function recoveryWorkerPassword()
    {
        if (HttpRequest::method() === 'GET')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if (!isset($DATA['recovery_code'])) {
                return [
                    'error' => [
                        'title' => 'No Recovery Code',
                        'message' => "No recovery code has been provided!"
                    ]
                ];
            }
            $code = $request->get('recovery_code');

            $validator = new RecoveryCodeValidator();
            $isValidCode = $validator->validate($code);
            if (!$isValidCode) {
                return [
                    'error' => [
                        'title' => 'Invalid Recovery Code',
                        'message' => "The provided recovery code is invalid by its characters!"
                    ]
                ];
            }

            $dateOfSending = $this->dataMapper->selectWorkerDateSendingByRecoveryCode($code);
            if (!$dateOfSending) {
                return [
                    'error' => [
                        'title' => 'Wrong Recovery Code',
                        'message' => "There is no worker record with such recovery code!"
                    ]
                ];
            }

            $dateTimestamp = (new \DateTime($dateOfSending))->getTimestamp();
            if (time() > $dateTimestamp + VALID_TIME_RECOVERY_CODE) {
                return [
                    'error' => [
                        'title' => 'Expired Recovery Code',
                        'message' => "The recovery code is already expired!"
                    ]
                ];
            }
           return [
               'success' => true,
               'recovery_code' => $code
           ];

        }
        return [
            'error' => [
                'title' => 'Invalid request type',
                'message' => "The request to recover password should be of GET type!"
            ]
        ];
    }

    /**
     * @return string[]|void
     */
    public function changeWorkerPassword()
    {
        if (HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!SessionHelper::getRecoveryCodeSession()
                || !isset($DATA['password'])
                || !isset($DATA['confirm-password']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                // $_SESSION['recovery_code'] has been set in the recoveryPassword()
                'recovery-code' => SessionHelper::getRecoveryCodeSession(),
                'password' => $request->get('password'),
                'confirm-password' => $request->get('confirm-password')
            ];
            $passValidator = new PasswordValidator();
            $pass1 = $passValidator->validate($items['password']);

            if (!$pass1) {
                return [
                    'error' => "Password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 and 30 characters long!",
                    'code' => HttpCode::unprocessableEntity()
                ];
            }

            if ($items['password'] !== $items['confirm-password']) {
                return [
                    'error' => "Password and its confirmation one are not equal!",
                    'code' => HttpCode::unprocessableEntity()
                ];
            }
            $validator = new RecoveryCodeValidator();
            $isValidCode = $validator->validate($items['recovery-code']);
            if (!$isValidCode) {
                return [
                    'error' => "The provided recovery code is invalid by its characters!",
                    'code' => HttpCode::unprocessableEntity()
                ];
            }
            $passwordHash = PasswordHasher::hash($items['password']);

            $changed = $this->dataMapper->updateWorkerPasswordByRecoveryCode(
                $items['recovery-code'], $passwordHash
            );
            if (!$changed) {
                return [
                    'error' => "An error occurred while changing the password!",
                    'code' => HttpCode::notFound()
                ];
            }

            $recoveryNullified = $this->dataMapper->updateRecoveryCodeByRecoveryCode(
                $items['recovery-code']
            );
            if(!$recoveryNullified) {
                return [
                    'error' => "An error occurred while updating the recovery code!",
                    'code' => HttpCode::notFound()
                ];
            }
            return [
                'success' => 'You successfully changed your password'
            ];
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }

    public function loginWorker()
    {
        if (HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(!isset($DATA['email']) || !isset($DATA['password']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                'email' => $request->get('email'),
                'password' => $request->get('password')
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
            $actualPasswordHash = $this->dataMapper->selectWorkerPasswordByEmail(
                $items['email']
            );
            if ($actualPasswordHash === false) {
                return [
                    'error' => 'There is no worker with such email',
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
                    'error' => 'The provided password does not match the one saved for the requested worker!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Select User ID for storing it into session
             */
            $workerId = $this->dataMapper->selectWorkerIdByEmail($items['email']);
            if ($workerId === false) {
                return [
                    'error' => 'The error occurred while getting worker id!',
                    'code' => HttpCode::notFound()
                ];
            }

            /**
             * Redirect the worker to the requested page (if such was attempted to be accessed)
             */
            $rememberUrl = SessionHelper::getRememberUrlSession('worker');
            $redirectUrl = $rememberUrl !== false ? $rememberUrl : WebApiProvider::workerHome();
            SessionHelper::removeAllRememberUrlSession();

            /**
             * Store Worker's ID into session
             */
            SessionHelper::setWorkerSession($workerId);
            return [
                'success' => true,
                'data' => [
                    'id' => SessionHelper::getWorkerSession(),
                    'redirect_url' => $redirectUrl
                ]
            ];
        }
        else {
            return $this->_methodNotAllowed(['POST']);
        }
    }
}