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

    public function send(bool $debug = false)
    {
       return $this->service->sendEmail($this->email, $debug);
    }
}