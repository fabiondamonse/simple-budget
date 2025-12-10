<?php

namespace App\Controllers;

use Symfony\Component\Routing\RouteCollection;
use App\Models\User;
use App\baseHelper;

class UserController
{

    public function loginAction(RouteCollection $routes)
    {
        global $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $redirectUrl = (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : baseHelper::getBaseUrl());

        if (!empty($_REQUEST['username']) && !empty($_REQUEST['login'])) {
            $username    = $_REQUEST['username'];
            $password    = $_REQUEST['login'];
            $users       = new User($db);
            $user        = $users->login($username, $password);
            $redirectUrl = (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : baseHelper::getBaseUrl());

            if (!empty($user['error'])) {
                $_SESSION['error'] = $user['error'];
            }
        }

        header("Location: $redirectUrl");
        die();
    }

}