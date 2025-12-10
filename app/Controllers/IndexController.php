<?php

namespace App\Controllers;

use Symfony\Component\Routing\RouteCollection;

class IndexController
{
    protected $_db;
    /**
     * @param int $id
     * @param RouteCollection $routes
     * @return void
     */
    public function __construct(){
        global $db;
        $this->_db = $db;
    }

    public function indexAction(RouteCollection $routes){
        require_once APP_ROOT . '/views/index.php';
    }

}