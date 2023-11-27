<?php

namespace Src\Service\Sender\impl\email\services\impl;

use Mailgun\Mailgun;
use Src\Helper\Mailgun\MailgunApiCredentials;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\MailingService;

class MailgunService implements MailingService
{
    private string $apiKey;
    private string $domain;
    private Mailgun $mailgun;

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

            $messageId = $response->getId();

            // Check if the response contains an ID to determine success
            if ($messageId) {
                return true;
            } else {
                return "Email sending failed. Response content: " . $response->getMessage();
            }
        } catch (\Mailgun\Exception\HttpClientException $e) {
            // Handle Mailgun HTTP client exceptions
            return "Mailgun HTTP client exception: " . $e->getMessage();
        } catch (\Exception $e) {
            // Handle other exceptions
            return "An unexpected error occurred: " . $e->getMessage();
        }
    }

}
