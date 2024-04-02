<?php

namespace Src\Helper\Email;

use Src\Service\Sender\impl\email\EmailSender;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\impl\MailgunService;

class WorkerEmailHelper
{
    protected static function _createRecoveryLink(string $recoveryCode) {
        return ENROLL_BEAUTY_URL_HTTP_ROOT.API['AUTH']['WEB']['WORKER']['recovery-password']."?recovery_code=$recoveryCode";
    }

    public static function sendLetterToWelcomeWorker(
        $email, $recoveryCode, $name, $surname
    ) {
        $email = new Email(
            COMPANY_EMAIL,
            COMPANY_NAME,
            [$email],
            'welcome',
            EMAIL_WITH_LINK,
        );

        $recoveryUrl = static::_createRecoveryLink($recoveryCode);
        $email->populateWorkerWelcomeLetter($recoveryUrl, $name, $surname);

        $sender = new EmailSender($email, new MailgunService());

        return $sender->send();
    }
}