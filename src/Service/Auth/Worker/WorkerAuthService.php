<?php

namespace Src\Service\Auth\Worker;

use Src\Helper\Builder\impl\UrlBuilder;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DTO\Write\WorkerWriteDTO;
use Src\Model\Entity\Gender;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;
use Src\Service\Generator\impl\PasswordGenerator;
use Src\Service\Generator\impl\RecoveryCodeGenerator;
use Src\Service\Hasher\impl\PasswordHasher;
use Src\Service\Sender\impl\email\EmailSender;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\impl\MailgunService;
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
    public function registerWorker(): array {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'name' => htmlspecialchars(trim($_POST['name'])),
                'surname' => htmlspecialchars(trim($_POST['surname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'position_id' => htmlspecialchars(trim($_POST['position_id'])),
                'role_id' => htmlspecialchars(trim($_POST['role_id'])),
                'gender' => htmlspecialchars(trim($_POST['gender'])),
                'age' => htmlspecialchars(trim($_POST['age'])),
                'experience' => htmlspecialchars(trim($_POST['experience'])),
                'salary' => htmlspecialchars(trim($_POST['salary'])),
            ];
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
                    'error' => 'Invalid position has been selected!'
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
                    'error' => 'Invalid role has been selected!'
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
                        'error' => 'Invalid gender selected! It should be Male, Female, or Other'
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
                    'error' => "The worker's age should be from 14 to 80 years!"
                ];
            }

            /**
             * Years of experience
             */
            if(!$items['experience']) {
                return [
                    'error' => "Years of worker's experience is required field!"
                ];
            }
            if($items['experience'] < 0 || $items['experience'] > 66) {
                return [
                    'error' => "The years of the worker's experience should be from 0 to 66 years!"
                ];
            }

            /**
             * Salary
             */
            if($items['salary']) {
                if($items['salary'] < 0){
                    return [
                        'error' => 'Salary can not be negative number!'
                    ];
                }
                if(!is_int((int)$items['salary']) && !is_double((double)$items['salary'])) {
                    return [
                        'error' => 'Invalid salary number is provided!'
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
                    'error' => 'The worker with such email has already been registered!'
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
                    'error' => 'An error occurred while inserting the worker into database!'
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
                    'error' => 'An error occurred while inserting the worker settings into database!'
                ];
            }

            /**
             * Insert worker social
             */
            $workerSocial = $this->dataMapper->insertWorkerSocial($workerId);
            if($workerSocial === false) {
                $this->dataMapper->rollBackTransaction();
                return [
                    'error' => 'An error occurred while inserting the worker social into database!'
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
                    'success' => "You successfully registered the worker '{$items['name']} {$items['surname']}'! The letter with the link to get access to the account has been sent to their email."
                ];
            }
        }
        return [];
    }

    protected function _createRecoveryLink(string $recoveryCode) {
        $builder = new UrlBuilder();
        $url = $builder->baseUrl(ENROLL_BEAUTY_URL_HTTP_ROOT)
                    ->controllerType('web')
                    ->controllerPrefix('worker')
                    ->controllerMethod('recoveryPassword')
                    ->get('recovery_code', $recoveryCode)
                 ->build();
        return $url;
    }

    protected function _sendLetterToWelcomeWorker(
        $email, $workerSettingId, $recoveryCode, $name, $surname
    ) {
        $email = new Email(
            COMPANY_EMAIL,
            COMPANY_NAME,
            [$email],
            'welcome',
            EMAIL_WITH_LINK,
        );

        $recoveryUrl = $this->_createRecoveryLink($recoveryCode);
        $email->populateWorkerWelcomeLetter($recoveryUrl, $name, $surname);

        $sender = new EmailSender($email, new MailgunService());
        $emailSent = $sender->send();
        //var_dump($emailSent);
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
                    'error' => 'Something went wrong while saving password recovery code!'
                ];
            }
        } else {
            return [
                'error' => $emailSent
            ];
        }
    }


    /**
     * @return array
     *
     * used by Web controller, so return 'title' too
     */
    public function recoveryWorkerPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['recovery_code'])) {
                $code = htmlspecialchars(trim($_GET['recovery_code']));

                $validator = new RecoveryCodeValidator();
                $isValidCode = $validator->validate($code);
                if (!$isValidCode) {
                    return [
                        'error' => [
                            'title' => 'Invalid Recovery Code',
                            'message' =>"The provided recovery code is invalid by its characters!"
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
                    'title' => 'No Recovery Code',
                    'message' => "No recovery code has been provided!"
                ]
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                // $_SESSION['recovery_code'] has been set in the recoveryPassword()
                'recovery-code' => SessionHelper::getRecoveryCodeSession(),
                'password' => htmlspecialchars(trim($_POST['password'] ?? '')),
                'confirm-password' => htmlspecialchars(trim($_POST['confirm-password'] ?? ''))
            ];
            $passValidator = new PasswordValidator();
            $pass1 = $passValidator->validate($items['password']);

            if (!$pass1) {
                return [
                    'error' => "Password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 and 30 characters long!"
                ];
            }

            if ($items['password'] !== $items['confirm-password']) {
                return [
                    'error' => "Password and its confirmation one are not equal!"
                ];
            }
            $validator = new RecoveryCodeValidator();
            $isValidCode = $validator->validate($items['recovery-code']);
            if (!$isValidCode) {
                return [
                    'error' => "The provided recovery code is invalid by its characters!"
                ];
            }
            $passwordHash = PasswordHasher::hash($items['password']);

            $changed = $this->dataMapper->updateWorkerPasswordByRecoveryCode(
                $items['recovery-code'], $passwordHash
            );
            if (!$changed) {
                return [
                    'error' => "An error occurred while changing the password!"
                ];
            }

            $recoveryNullified = $this->dataMapper->updateRecoveryCodeByRecoveryCode(
                $items['recovery-code']
            );
            if(!$recoveryNullified) {
                return [
                    'error' => "An error occurred while updating the recovery code!"
                ];
            }
            return [
                'success' => 'You successfully changed your password'
            ];
        }
    }

    public function loginWorker() {
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
            $actualPasswordHash = $this->dataMapper->selectWorkerPasswordByEmail(
                $items['email']
            );
            if ($actualPasswordHash === false) {
                return [
                    'error' => 'There is no worker with such email'
                ];
            }

            /**
             * Check Password Equality
             */
            $hashValidator = new PasswordHashValidator($actualPasswordHash);
            $validPassword = $hashValidator->validate($items['password']);
            if (!$validPassword) {
                return [
                    'error' => 'The provided password does not match the one saved for the requested worker!'
                ];
            }

            /**
             * Select User ID for storing it into session
             */
            $workerId = $this->dataMapper->selectWorkerIdByEmail($items['email']);
            if ($workerId === false) {
                return [
                    'error' => 'The error occurred while getting worker id!'
                ];
            }

            /**
             * Store Worker's ID into session
             */
            SessionHelper::setWorkerSession($workerId);
            return [
                'success' => true,
                'data' => [
                    'session' => SessionHelper::getWorkerSession()
                ]
            ];
        }
        return [];
    }
}