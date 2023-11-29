<?php

namespace Src\Service\Sender\impl\email\services\impl;

use Mailgun\Mailgun;
use Src\Helper\Logger\ILogger;
use Src\Helper\Logger\impl\MyLogger;
use Src\Helper\Mailgun\MailgunApiCredentials;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\MailingService;

class MailgunService implements MailingService
{
    private string $apiKey;
    private string $domain;
    private Mailgun $mailgun;
    private ILogger $logger;

    /**
     * @param string $api_key
     * @param string $domain
     */
    public function __construct($apiKey = null, $domain = null, ILogger $logger = null)
    {
        $this->apiKey = $apiKey ?? MailgunApiCredentials::$apiKey;
        $this->domain = $domain ?? MailgunApiCredentials::$domain;
        $this->logger = $logger ?? MyLogger::getInstance();

        // Initialize the Mailgun client
        $this->mailgun = Mailgun::create($this->apiKey);
    }

    private function createMessage(Email $email, bool $debug = false): array
    {
        $message = [
            'from' => "{$email->getFromName()} <{$email->getFromEmail()}>",
            'to' => $email->getRecipients(),
            'subject' => $email->getTopic(),
            'html' => $email->getMessageTemplate(),
        ];
        $this->addAttachment($email, $message);

        return $message;
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

    public function _sendEmail(Email $email, bool $debug = false)
    {
        $message = $this->createMessage($email, $debug);

        // Send the email using the Mailgun client
        $response = $this->mailgun->messages()->send($this->domain, $message);

        $messageId = $response->getId();
        if ($response->getId()) {
            return true;
        } else {
            return "Email sending failed. Response content: " . $response->getMessage();
        }
    }

    public function sendEmail(Email $email, bool $debug = false)
    {
        try {
            $message = $this->createMessage($email, $debug);

            // Send the email using the Mailgun client
            $response = $this->mailgun->messages()->send($this->domain, $message);

            /**
             * Email has been successfully delivered
             */
            if (str_contains($response->getMessage(), 'Queued')) {
                return true;
            } else {
                $errorMessage = "Email sending failed. Response content: " . $response->getMessage();
                $this->logger->error($errorMessage);
                return 'Email sending failed';
            }
        } catch (\Mailgun\Exception\HttpClientException $e) {
            $errorMessage = "Mailgun HTTP client exception: " . $e->getMessage();
            $this->logger->error($errorMessage);
            return 'Email sending failed';
        } catch (\Exception $e) {
            $errorMessage = "An unexpected error occurred: " . $e->getMessage();
            $this->logger->error($errorMessage);
            return 'Email sending failed';
        }
    }

}
