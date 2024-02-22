<?php

namespace Src\Service\Sender\impl\email\services\impl;

use Mailgun\Exception\HttpClientException;
use Mailgun\Mailgun;
use Src\Helper\Credentials\API\MailgunApiCredentials;
use Src\Helper\Logger\ILogger;
use Src\Helper\Logger\impl\MyLogger;
use Src\Helper\Uploader\impl\FileUploader;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\MailingService;

class MailgunService implements MailingService
{
    private string $apiKey;
    private string $domain;
    private Mailgun $mailgun;
    private ILogger $logger;

    public MailingService $alternateMailer;

    /**
     * @param string $api_key
     * @param string $domain
     */
    public function __construct($apiKey = null, $domain = null)
    {
        $this->apiKey = $apiKey ?? MailgunApiCredentials::$apiKey;
        $this->domain = $domain ?? MailgunApiCredentials::$domain;

        // Initialize the Mailgun client
        $this->mailgun = Mailgun::create($this->apiKey);
        $this->logger = MyLogger::getInstance();
    }

    public function setAlternateMailer(MailingService $service = null)
    {
        $this->alternateMailer = $service ?? new BrevoService();
    }

    public function getAlternateMailer(): MailingService
    {
        return $this->alternateMailer;
    }

    private function createMessage(Email $email, bool $debug = false): array
    {
        $message = [
            'from'    => "{$email->getFromName()} <{$email->getFromEmail()}>",
            'to'      => $email->getRecipients(),
            'subject' => $email->getTopic(),
            'html'    => $email->getMessageTemplate(),
        ];
        $this->addAttachment($email, $message);
        $this->addInlineImages($email, $message);

        return $message;
    }

    /**
     * @param Email $email - contains array of [
     *      0 => [
     *          'filePath' => path to the image, which has been uploaded into the appropriate
     *                        temporary folder before creating the email object
     *      ]
     *      ....................................................
     * ]
     *
     * and its $email->messageTemplate should contain in its html the image
     * represented like <img src="cid:filename"> to be properly rendered then
     *
     * @param array $message
     * @return void
     */
    private function addInlineImages(Email $email, array &$message)
    {
        $images = $email->getInlineImages();
        if (count($images) > 0) {
            $message['inline'] = $images;
        }
    }

    private function addAttachment(Email $email, array &$message)
    {
        $attachments = $email->getAttachments();
        if (!empty($attachments)) {
            foreach ($attachments as $filePath => $fileContent) {
                $message['attachment'][] = [
                    'filePath' => $filePath,
                    'filename' => basename($filePath),
                ];
            }
        }
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

    public function sendEmail(Email $email, bool $debug = false)
    {
        $message = $this->createMessage($email, $debug);

        try {
            $response = $this->mailgun->messages()->send($this->domain, $message);
            return $this->_successSendingEmail();
        }
        catch (HttpClientException $e) {
            $this->_recordErrorIntoLogs("Mailgun HTTP client exception: " . $e->getMessage());
            return $this->_errorSendingEmail();
        }
        catch (\Exception $e) {
            $this->_recordErrorIntoLogs("An unexpected error occurred: " . $e->getMessage());
            return $this->_errorSendingEmail();
        }
    }
}
