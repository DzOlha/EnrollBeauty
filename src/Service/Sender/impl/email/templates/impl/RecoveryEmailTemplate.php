<?php

namespace Src\Service\Sender\impl\email\templates\impl;

use Src\Service\Sender\impl\email\templates\EmailTemplate;

class RecoveryEmailTemplate extends EmailTemplate
{
    public static string $urlOnButton = '%URL_ON_BUTTON%';
    public static string $buttonText = '%BUTTON_TEXT%';
}