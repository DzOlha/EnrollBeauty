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
    public static function setWorkerSession(int $workerId): void {
        $_SESSION['worker_id'] = $workerId;
    }
    public static function getWorkerSession() {
        if(isset($_SESSION['worker_id'])) {
            return $_SESSION['worker_id'];
        } else {
            return false;
        }
    }
    public static function removeWorkerSession(): void {
        unset($_SESSION['worker_id']);
    }

    /***
     * Admin
     */
    public static function setAdminSession(int $adminId) {
        $_SESSION['admin_id'] = $adminId;
    }
    public static function getAdminSession() {
        if(isset($_SESSION['admin_id'])) {
            return $_SESSION['admin_id'];
        } else {
            return false;
        }
    }
    public static function removeAdminSession() {
        unset($_SESSION['admin_id']);
    }
}