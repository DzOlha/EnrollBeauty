<?php

namespace Src\Helper\Data;

class AdminDefault
{
    public static function getRegistrationUrl() {
        $admin = self::getAdminDefault();
        $registerAdminUrl = hash('sha256', $admin['registration_key']);

        if($registerAdminUrl === $admin['registration_url']) {
            return $registerAdminUrl;
        }
        return false;
    }
    public static function getAdminDefault() {
        $adminConfigFile = ADMIN_DEFAULT_CREDENTIALS;
        $admin = include $adminConfigFile;

        return $admin;
    }

    public static function getAdminDefaultPassword() {
        return self::getAdminDefault()['password'];
    }
    public static function getAdminDefaultEmail() {
        return self::getAdminDefault()['email'];
    }
}