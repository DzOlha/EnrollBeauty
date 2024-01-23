<?php

namespace Src\Helper\Session;

class SessionHelper
{
    /**
     * User
     */
    public static function setUserSession(int $userId): void
    {
        $_SESSION['user_id'] = $userId;
    }

    public static function getUserSession()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else {
            return false;
        }
    }

    public static function removeUserSession(): bool
    {
        if(SessionHelper::getUserSession()) {
            unset($_SESSION['user_id']);
            return true;
        }
        return false;
    }

    /***
     * Worker
     */
    public static function setWorkerSession(int $workerId): void
    {
        $_SESSION['worker_id'] = $workerId;
    }

    public static function getWorkerSession()
    {
        if (isset($_SESSION['worker_id'])) {
            return $_SESSION['worker_id'];
        } else {
            return false;
        }
    }

    public static function removeWorkerSession(): bool
    {
        if(SessionHelper::getWorkerSession()) {
            unset($_SESSION['worker_id']);
            return true;
        }
        return false;
    }

    /***
     * Admin
     */
    public static function setAdminSession(int $adminId)
    {
        $_SESSION['admin_id'] = $adminId;
    }

    public static function getAdminSession()
    {
        if (isset($_SESSION['admin_id'])) {
            return $_SESSION['admin_id'];
        } else {
            return false;
        }
    }

    public static function removeAdminSession()
    {
        if(SessionHelper::getAdminSession()) {
            unset($_SESSION['admin_id']);
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
        $_SESSION['recovery_code'] = $recoveryCode;
    }

    public static function getRecoveryCodeSession()
    {
        if (isset($_SESSION['recovery_code'])) {
            return $_SESSION['recovery_code'];
        } else {
            return false;
        }
    }

    public static function removeRecoveryCodeSession(): bool
    {
        if(SessionHelper::getRecoveryCodeSession()) {
            unset($_SESSION['recovery_code']);
            return true;
        }
        return false;
    }
}