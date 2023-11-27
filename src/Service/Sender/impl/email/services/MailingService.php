<?php

namespace Src\Service\Sender\impl\email\services;

use Src\Service\Sender\impl\email\model\Email;

interface MailingService
{
    public function sendEmail(Email $email, bool $debug = false);
}