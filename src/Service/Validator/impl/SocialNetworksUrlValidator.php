<?php

namespace Src\Service\Validator\impl;

class SocialNetworksUrlValidator
{
    private array $patterns = [
        'Instagram' => '/^https:\/\/www\.instagram\.com\/[a-zA-Z0-9_]+\/?(?:\?[\w=&]+)?$/',
        'Facebook' => '/^https:\/\/www\.facebook\.com\/(profile\.php\?id=)?[0-9]{1,}$/',
        'TikTok' => '/^https:\/\/www\.tiktok\.com\/@[\w.-]+(\?_t=.+)?$/',
        'YouTube' => '/^https:\/\/youtube\.com\/@[\w.-]+(?:\?si=[a-zA-Z0-9_-]+)?$/',
        'LinkedIn' => '/^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9_-]+(\?.*)?$/',
        'Github' => '/^https:\/\/github\.com\/[a-zA-Z0-9-]+$/',
        'Telegram' => '/^https:\/\/t\.me\/[a-zA-Z0-9_]+$/',
    ];

    /**
     * @param array $data = [
     *      'Instagram' => instagram profile url,
     *      'Facebook' => facebook profile url,
     *      'TikTok' => tiktok profile url,
     *      'YouTube' => youtube profile url,
     *      'LinkedIn' => linkedin profile url,
     *      'Github' => github profile url,
     *      'Telegram' => telegram profile url
     * ]
     * @return string|bool
     */
    public function validateAll(array &$data): string|bool
    {
        $errors = '';
        foreach ($data as $key => &$value) {
            if(!$value) {
                $value = null;
                continue;
            };
            if(!preg_match($this->patterns[$key], $value)) {
                $errors .= " Invalid url provided for $key profile! "."<br>";
            } else {
                $value = $this->_returnUrlWithoutDomain(WORKER_SOCIAL_NETWORKS_ROOT_URLS[$key], $value);
                if($value === false) {
                    $errors .= " Invalid url provided for $key profile! "."<br>";
                }
                if(strlen($value) > 255) {
                    $errors .= " Url for $key profile exceed 255 characters! "."<br>";
                }
            }
        }
        if(strlen($errors) > 0) {
            return $errors;
        }
        return true;
    }

    /**
     * @param $domain
     * @param $profileUrl
     * @return false|string
     *
     * Process the url to get rid of the host name of the website
     * Example:
     *      $domain = 'https://t.me/'
     *      $profileUrl = 'https://t.me/my_username_in_telegram'
     *
     * Return: 'my_username_in_telegram'
     */
    private function _returnUrlWithoutDomain($domain, $profileUrl): string | false
    {
        // Check if the domain is present in the profile URL
        if (str_starts_with($profileUrl, $domain)) {
            $profilePathWithoutDomain = substr($profileUrl, strlen($domain));
            return $profilePathWithoutDomain;
        } else {
            return false;
        }
    }
}