<?php

/**
 * The baseHelper class provides utility methods for application helpers.
 */

namespace App;

class baseHelper
{
    static public function getBaseUrl(): string
    {
        return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
        );
    }
}