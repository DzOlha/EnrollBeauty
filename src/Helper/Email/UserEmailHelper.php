<?php

namespace Src\Helper\Email;

use Src\Service\Sender\impl\email\EmailSender;
use Src\Service\Sender\impl\email\model\Email;
use Src\Service\Sender\impl\email\services\impl\MailgunService;

class UserEmailHelper
{
    protected static function _createLinkToLogin()
    {
        return COMPANY['url_https'] . API['AUTH']['WEB']['USER']['login'];
    }


    public static function sendLetterToInformUserAboutCancellation(
        $email, $name, $surname, $order
    ){
        $email = new Email(
            COMPANY['email'],
            COMPANY['name'],
            [$email],
            'order_canceled',
            EMAIL_WITH_LINK,
        );

        $loginUrl = static::_createLinkToUserProfile();
        $email->populateWorkerWelcomeLetter(
            $loginUrl, $name, $surname, 'Enroll Beauty', 'Order Cancellation',
            "Your order '{$order['name']}' on {$order['start_datetime']} has been cancelled by the master. Please, log in to check your orders and schedule new appointments!",
            'Visit my account!'
        );

        $sender = new EmailSender($email, new MailgunService());

        return $sender->send();
    }

    protected static function _createLinkToUserProfile()
    {
        return COMPANY['url_https'] . API['USER']['WEB']['PROFILE']['home'];
    }

    public static function sendLetterToWelcomeUser(
        $email, $name, $surname
    ) {
        $email = new Email(
            COMPANY['email'],
            COMPANY['name'],
            [$email],
            'welcome',
            EMAIL_WITH_LINK,
        );

        $profileUrl = static::_createLinkToLogin();
        $email->populateUserWelcomeLetter($profileUrl, $name, $surname);

        $sender = new EmailSender($email, new MailgunService());

        return $sender->send();
    }

    public static function sendLetterToInformUserAboutServiceOrder(
        $email, $order
    ){
        $email = new Email(
            COMPANY['email'],
            COMPANY['name'],
            [$email],
            'order_details',
            EMAIL_WITH_LINK,
        );

        $loginUrl = static::_createLinkToUserProfile();
        $worker = $order['worker_name'].' '.$order['worker_surname'];
        $address = $order['city'].', '.$order['address'];
        $datetime = date('d F, H:i', strtotime($order['start_datetime']));
        $price = $order['price'].' '.$order['currency'];

        $email->populateWorkerWelcomeLetter(
            $loginUrl, $order['user_name'], $order['user_surname'], 'Enroll Beauty', 'Order Details',
            "Your order '{$order['service_name']}' for a total sum of $price with number <b>{$order['id']}</b> has been confirmed. The beauty master $worker will be looking forward to meeting you on $datetime at $address.",
            'Visit my account!'
        );

        $sender = new EmailSender($email, new MailgunService());

        return $sender->send();
    }
}