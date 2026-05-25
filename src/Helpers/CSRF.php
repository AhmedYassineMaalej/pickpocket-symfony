<?php

namespace App\Helpers;

class CSRF
{
    public static function generate_token()
    {
        $csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;
        return $csrf_token;
    }

    public static function validate_token($token)
    {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            $_SESSION['csrf_token'] = null;
            error_log("Invalid CSRF token");
            return false;
        }
        $_SESSION['csrf_token'] = null;
        return true;
    }
}
