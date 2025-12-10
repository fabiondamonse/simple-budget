<?php
use App\Db;
require_once '../config/config.php';
require_once '../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(ENVIRONMENT === 'development'){
    error_reporting(E_ALL);
    error_reporting(-1);
    ini_set('error_reporting', E_ALL);
}

// set global database object
$db = new DB(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// set php timezone
date_default_timezone_set('Africa/Johannesburg');

// Routes
require_once '../routes/web.php';
require_once '../app/Router.php';

