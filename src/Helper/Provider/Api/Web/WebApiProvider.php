<?php

namespace Src\Helper\Provider\Api\Web;

class WebApiProvider
{
    public static function userLogin()
    {
        return API['AUTH']['WEB']['USER']['login'];
    }

    public static function userHome()
    {
        return API['USER']['WEB']['PROFILE']['home'];
    }

    public static function workerLogin()
    {
        return API['AUTH']['WEB']['WORKER']['login'];
    }
    public static function workerHome()
    {
        return API['WORKER']['WEB']['PROFILE']['home'];
    }

    public static function adminLogin()
    {
        return API['AUTH']['WEB']['ADMIN']['login'];
    }
    public static function adminHome()
    {
        return API['ADMIN']['WEB']['PROFILE']['home'];
    }

}