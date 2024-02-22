<?php

namespace Src\Service\Sender\impl\email\services\impl;

use Brevo\Client\Api\TransactionalEmailsApi;
use Src\Helper\Credentials\API\BrevoApiCredentials;
use Src\Helper\Logger\ILogger;
use Src\Helper\Logger\impl\MyLogger;
use Src\Helper\Uploader\impl\FileUploader;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\MailingService;
use Brevo\Client\Configuration;
use GuzzleHttp\Client;
use Brevo\Client\Model\SendSmtpEmail;

class BrevoService implements MailingService
{
    private string $apiKey;
    private TransactionalEmailsApi $apiInstance;
    private ILogger $logger;
    private MailingService $alternateMailer;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey = null, ILogger $logger = null)
    {
        $this->logger = $logger ?? new MyLogger();
        $this->apiKey = $apiKey ?? BrevoApiCredentials::$apiKey;

        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'api-key', $this->apiKey
        );

        $this->apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
    }

    /**
     * @param Email $email
     * @param bool $debug
     * @return SendSmtpEmail
     *
     * IMPORTANT: api docs is in https://developers.brevo.com/docs/getting-started
     * Max amount of recipients is 99 at once!
     */
    private function createMessage(Email $email, bool $debug = false): SendSmtpEmail
    {
        $resultArray = [];

        foreach ($email->getRecipients() as $emailString) {
            $resultArray[] = ['name' => 'Dear User', 'email' => $emailString];
        }

        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $email->getTopic(),
            'sender' => [
                'name' => $email->getFromName(),
                'email' => $email->getFromEmail()
            ],
            'replyTo' => [
                'name' => $email->getFromName(),
                'email' => $email->getFromEmail()
            ],
            'to' => $resultArray,
            'htmlContent' => $email->getMessageTemplate(),
            'params' => [
                'bodyMessage' => ''
            ]
        ]);

        return $sendSmtpEmail;
    }


    public function sendEmail(Email $email, bool $debug = false)
    {
        $message = $this->createMessage($email, $debug);

        try {
            $result = $this->apiInstance->sendTransacEmail($message);
            return $this->_successSendingEmail();
        }
        catch (\Exception $e) {
            $this->_recordErrorIntoLogs(
                "Email sending failed. Response content: ". $e->getMessage()
            );
            return $this->_errorSendingEmail();
        }
    }

    public function setAlternateMailer(MailingService $service = null)
    {
        $this->alternateMailer = $service ?? new MailgunService();
    }

    public function getAlternateMailer(): MailingService
    {
        return $this->alternateMailer;
    }

    private function _errorSendingEmail(string $message = 'Email sending failed'): string
    {
        FileUploader::deleteFolder(TEMP_EMAIL_IMAGES_UPLOAD_FOLDER);
        return $message;
    }

    private function _successSendingEmail(): bool
    {
        FileUploader::deleteFolder(TEMP_EMAIL_IMAGES_UPLOAD_FOLDER);
        return true;
    }

    private function _recordErrorIntoLogs(string $message)
    {
        $this->logger->error($message);
    }
}