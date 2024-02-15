<?php

namespace Src\Helper\Session;

class SessionHelper
{
    private static string $admin_id = 'admin_id';
    private static string $worker_id = 'worker_id';
    private static string $user_id = 'user_id';

    private static string $recovery_code = 'recovery_code';
    /**
     * User
     */
    public static function setUserSession(int $userId): void
    {
        $_SESSION[SessionHelper::$user_id] = $userId;
    }

    public static function getUserSession()
    {
        if (isset($_SESSION[SessionHelper::$user_id])) {
            return $_SESSION[SessionHelper::$user_id];
        } else {
            return false;
        }
    }

    public static function removeUserSession(): bool
    {
        if(SessionHelper::getUserSession()) {
            unset($_SESSION[SessionHelper::$user_id]);
            return true;
        }
        return false;
    }

    /***
     * Worker
     */
    public static function setWorkerSession(int $workerId): void
    {
        $_SESSION[SessionHelper::$worker_id] = $workerId;
    }

    public static function getWorkerSession()
    {
        if (isset($_SESSION[SessionHelper::$worker_id])) {
            return $_SESSION[SessionHelper::$worker_id];
        } else {
            return false;
        }
    }

    public static function removeWorkerSession(): bool
    {
        if(SessionHelper::getWorkerSession()) {
            unset($_SESSION[SessionHelper::$worker_id]);
            return true;
        }
        return false;
    }

    /***
     * Admin
     */
    public static function setAdminSession(int $adminId)
    {
        $_SESSION[SessionHelper::$admin_id] = $adminId;
    }

    public static function getAdminSession()
    {
        if (isset($_SESSION[SessionHelper::$admin_id])) {
            return $_SESSION[SessionHelper::$admin_id];
        } else {
            return false;
        }
    }

    public static function removeAdminSession()
    {
        if(SessionHelper::getAdminSession()) {
            unset($_SESSION[SessionHelper::$admin_id]);
            return true;
        }
        return false;
    }

    /**
     * Other session
     *
     * Recovery Code
     */
    public static function setRecoveryCodeSession(string $recoveryCode): void
    {
        $_SESSION[SessionHelper::$recovery_code] = $recoveryCode;
    }

    public static function getRecoveryCodeSession()
    {
        if (isset($_SESSION[SessionHelper::$recovery_code])) {
            return $_SESSION[SessionHelper::$recovery_code];
        } else {
            return false;
        }
    }

    public static function removeRecoveryCodeSession(): bool
    {
        if(SessionHelper::getRecoveryCodeSession()) {
            unset($_SESSION[SessionHelper::$recovery_code]);
            return true;
        }
        return false;
    }
}