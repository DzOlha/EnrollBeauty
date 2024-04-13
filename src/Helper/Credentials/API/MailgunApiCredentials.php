<?php

namespace Src\Helper\Credentials\API;

class MailgunApiCredentials
{
    public static string $apiKey = API_CREDENTIALS['EMAIL']['Mailgun']['api_key'];
    public static string $domain = API_CREDENTIALS['EMAIL']['Mailgun']['domain'];
}