<?php

namespace Src\Model\Table;

class UsersPhoto
{
    public static string $table = 'users_photo';
    public static string $id = "users_photo.id";
    public static string $user_id = 'users_photo.user_id';
    public static string $filename = 'users_photo.filename';
    public static string $is_main = 'users_photo.is_main';
}