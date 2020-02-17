<?php


namespace App\Helpers;


class SecurityHelper
{
    public static function getHashedPassword($password)
    {
        $hashedPassword= hash('sha256', $password, FALSE);
        return $hashedPassword;
    }
}
