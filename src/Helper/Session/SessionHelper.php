<?php

namespace Src\Helper\Session;

class SessionHelper
{
    /**
     * User
     */
    public static function setUserSession(int $userId): void {
        $_SESSION['user_id'] = $userId;
    }
    public static function getUserSession() {
        if(isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else {
            return false;
        }
    }
    public static function removeUserSession(): void {
        unset($_SESSION['user_id']);
    }

    /***
     * Worker
     */
    public static function setWorkerSession() {

    }
    public static function removeWorkerSession() {

    }

    /***
     * Admin
     */
    public static function setAdminSession() {

    }
    public static function removeAdminSession() {

    }
}