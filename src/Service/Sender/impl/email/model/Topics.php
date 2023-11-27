<?php

namespace Src\Service\Sender\impl\email\model;

class Topics
{
    private static array $topics = [
        'register' => 'Confirmation of registration',
        'welcome' => 'Welcome to the Sylin!',
        'referral_reg' => 'NEW_REG',
        'mail_auth' => 'Mail Authentication Connection',
        'g2fa' => 'Connecting Google Authenticator',
        'send_contact_form' => 'WE_RECEIVED_YOUR_REQUEST',
        'unsubscribe' => 'WE_RECEIVED_YOUR_REQUEST',
    ];
    public static function getAllTopics(): array {
        return static::$topics;
    }
    public static function getTopicByName(string $topicName) {
        if(key_exists($topicName, static::$topics)) {
            return static::$topics[$topicName];
        } else {
            return false;
        }
    }
}