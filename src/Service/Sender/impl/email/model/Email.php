<?php

namespace Src\Service\Sender\impl\email\model;

use Src\Service\Sender\impl\email\templates\impl\RecoveryEmailTemplate;

class Email
{
    private EmailAuthor $author;
    private array $recipients;
    private string $topic;
    private string $messageTemplate;
    private array $attachments;
    private array $inlineImages;

    public function __construct(
        string $fromEmail, string $fromName, array $recipients,
        string $topic, string $templateFilePath, array $attachments = [],
        string $lang = null, array $inlineImages = []
    ) {
        $this->messageTemplate = $this->setTemplate($templateFilePath);
        $this->attachments = $attachments ? $this->setAttachments($attachments) : $attachments;

        $this->setInlineImages($inlineImages);

        $this->author = new EmailAuthor($fromEmail, $fromName);
        $this->recipients = $recipients;
        $this->topic = Topics::getTopicByName($topic, $lang);
    }

    private function setTemplate($templateFilePath)
    {
        if (file_exists($templateFilePath)) {
            return file_get_contents($templateFilePath);
        } else {
            exit("There is no such file at location: $templateFilePath");
        }
    }

    /**
     * @param array $images
     * @return void
     *
     * $images = [
     *      0 => [
     *          'filePath' => path_to_the_image_inside_the_temporary_upload_folder
     *      ]
     * ]
     *
     * $contentId is just a name of the file that can be found by 'filePath'
     *
     * in html of the email all images
     * should be represented like <img src="cid:{filename}">
     */
    private function setInlineImages(array $images)
    {
        $this->inlineImages = $images;
    }

    public function getInlineImages(): array
    {
        return $this->inlineImages;
    }

    private function setAttachments(array $attachments)
    {
        $result = [];
        foreach ($attachments as $key => $filePath) {
            if (file_exists($filePath)) {
                $result += [
                    $filePath => file_get_contents($filePath)
                ];
            } else {
                exit("There is no such file at location: $filePath");
            }
        }
        return $result;
    }

    public function replaceArgumentWithValue($argName, $value)
    {
        $this->messageTemplate = preg_replace($argName, $value, $this->messageTemplate);
    }

    public function populateWorkerWelcomeLetter(
        string $urlOnButton,
        string $name,
        string $surname,
        string $headline = 'Enroll Beauty',
        string $title = 'Access to the account!',
        string $text = 'You have been registered as a Worker on the Enroll Beauty Platform. To get access to your account, please click the link below!',
        string $buttonText = 'Change password',
    )
    {
        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$headline.'/', $headline);

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$title.'/', "Dear, $name $surname");

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$text.'/', $text);

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$buttonText.'/',$buttonText);

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$urlOnButton.'/', $urlOnButton);

    }
    public function populateUserWelcomeLetter(
        string $urlOnButton,
        string $name,
        string $surname,
        string $headline = 'Enroll Beauty',
        string $title = 'Access to the account!',
        string $text = 'You have registered on the Enroll Beauty Platform. We appreciate your interest in our services and hope you will enjoy using them in your account, which can be opened by the link below!',
        string $buttonText = 'Visit My Account',
    )
    {
        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$headline.'/', $headline);

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$title.'/', "Dear, $name $surname");

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$text.'/', $text);

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$buttonText.'/',$buttonText);

        $this->replaceArgumentWithValue('/'.RecoveryEmailTemplate::$urlOnButton.'/', $urlOnButton);

    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->author->getFromEmail();
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->author->getFromName();
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return string
     */
    public function getMessageTemplate(): string
    {
        return $this->messageTemplate;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

}