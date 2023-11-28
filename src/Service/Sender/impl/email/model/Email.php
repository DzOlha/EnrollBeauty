<?php

namespace Src\Service\Sender\impl\email\model;

class Email
{
    private EmailAuthor $author;
    private array $recipients;
    private string $topic;
    private string $messageTemplate;
    private array $attachments;

    public function __construct(
        string $fromEmail, string $fromName, array $recipients,
        string $topic, string $templateFilePath, array $attachments = []
    )
    {
        $this->messageTemplate = $this->setTemplate($templateFilePath);
        $this->attachments = $attachments ? $this->setAttachments($attachments) : $attachments;

        $this->author = new EmailAuthor($fromEmail, $fromName);
        $this->recipients = $recipients;
        $this->topic = Topics::getTopicByName($topic);

        //$this->addArguments($_POST); // replace argument names with values
    }

    private function setTemplate($templateFilePath)
    {
        if (file_exists($templateFilePath)) {
            return file_get_contents($templateFilePath);
        } else {
            exit("There is no such file at location: $templateFilePath");
        }
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

    /**
     * Если есть что, добавляет доп. данные из формы для шаблона.
     * @param array $args
     * @return void
     */
    private function addArguments(array $args)
    {
        $this->messageTemplate = preg_replace('/%SUM%/', $args['sum'] ?? '', $this->messageTemplate);
        $this->messageTemplate = preg_replace('/%ARG1%/', $args['arg1'] ?? '', $this->messageTemplate);
        $this->messageTemplate = preg_replace('/%ARG2%/', $args['arg2'] ?? '', $this->messageTemplate);
        $this->messageTemplate = preg_replace('/%MAIL%/', $args['mail'] ?? '', $this->messageTemplate);
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
        $this->replaceArgumentWithValue('/%HEADLINE%/', $headline);

        $this->replaceArgumentWithValue('/%TITLE%/', "Dear, $name $surname");

        $this->replaceArgumentWithValue('/%TEXT%/', $text);

        $this->replaceArgumentWithValue('/%BUTTON_TEXT%/',$buttonText);

        $this->replaceArgumentWithValue('/%URL_ON_BUTTON%/', $urlOnButton);

    }
    public function populateEmailActivationLetter(string $activationLink) {
        $this->replaceArgumentWithValue('/%ARG1%/', $activationLink);

        $this->replaceArgumentWithValue('/%ARG1_link_text%/',
            "Account Activation");

        $this->replaceArgumentWithValue('/%ARG1_message_text%/',
            "Activation Letter Text");

        $this->replaceArgumentWithValue('/%ARG1_have_questions_text%/',
            "If you have any questions, please write to us by mail");

        $this->replaceArgumentWithValue('/%ARG1_was_not_you_text%/',
            "If it wasn't you, ");

        $this->replaceArgumentWithValue('/%ARG1_let_us_know_text%/',
            "let us know!");

        $this->replaceArgumentWithValue('/%ARG1_unsubscribe_text%/',
            "Unsubscribe_message");

        $this->replaceArgumentWithValue('/%ARG1_unsubscribe_link%/',
            "link");

        $this->replaceArgumentWithValue('/%ARG1_telegram_bot%/', 'https://telegram.my.bot');
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