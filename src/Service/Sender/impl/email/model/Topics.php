<?php

namespace Src\Service\Sender\impl\email\model;

class Topics
{
    private static array $topics = [
        'order_details' => 'Order Details',
        'order_canceled' => 'Order Cancellation!',
        'welcome' => 'Welcome to the Enroll Beauty!',
    ];
    public static function getAllTopics(): array {
        return static::$topics;
    }
    public static function getTopicByName(string $topicName, $lang = null) {
        if(key_exists($topicName, static::$topics)) {
            return static::$topics[$topicName];
        } else {
            return false;
        }
    }
}