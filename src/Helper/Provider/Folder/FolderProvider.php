<?php

namespace Src\Helper\Provider\Folder;

class FolderProvider
{
    public static function workerUploadsImg(int $workerId)
    {
        return WORKERS_PHOTO_FOLDER . "worker_$workerId/";
    }

    public static function userUploadsImg(int $userId)
    {
        return USERS_PHOTO_FOLDER . "user_$userId/";
    }

    public static function adminUploadsDepartmentImg(int $departmentId)
    {
        return ADMINS_PHOTO_FOLDER . "departments/department_$departmentId/";
    }
}