<?php

namespace Src\Service\Sender\impl\email;

use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\MailingService;
use Src\Service\Sender\Sender;

class EmailSender implements Sender
{
    private Email $email;
    private MailingService $service;

    public function __construct(Email $email, MailingService $service)
    {
        $this->email = $email;
        $this->service = $service;
    }

    public function send(bool $debug = false, int $newResentStatus = 0)
    {
        /**
         * If we need repeat the email sending, but with usage
         * of the alternate mailer service
         */
//        if($newResentStatus === 1) {
//            return $this->_sentByAlternateMailer($debug);
//        }

        $result = $this->service->sendEmail($this->email, $debug);

        /**
         * If the first attempt with the selected mailer was failed
         */
        if($result !== true) {
            return $this->_sentByAlternateMailer($debug);
        }
        return true;
    }

    private function _sentByAlternateMailer($debug)
    {
        /**
         * Set alternative mailer defined in the first mailer class
         */
        $this->service->setAlternateMailer();

        /**
         * Get alternate mailer
         */
        $alternateMailer = $this->service->getAlternateMailer();

        /**
         * Send the email again with usage of alternate mailer
         */
        $newAttemptResult = $alternateMailer->sendEmail($this->email, $debug);

        /**
         * Return the result to the client (success or failure)
         */
        return $newAttemptResult;
    }
}