<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

// Route system
$routes = new RouteCollection();

$routes->add('homepage', new Route('/', array('controller' => 'IndexController', 'method'=>'indexAction'), []));
$routes->add('task', new Route(constant('URL_SUBFOLDER') . '/task/{id}', array('controller' => 'TaskController', 'method'=>'showAction'), array('id' => '[0-9]+')));
$routes->add('taskList', new Route(constant('URL_SUBFOLDER') . '/taskList', array('controller' => 'TaskController', 'method'=>'listAction'), []));
$routes->add('addTask', new Route(constant('URL_SUBFOLDER') . '/addTask', array('controller' => 'TaskController', 'method'=>'addAction'), []));
$routes->add('login', new Route('/login', array('controller' => 'UserController', 'method'=>'loginAction'), []));
//$routes->add('updateTask', new Route(constant('URL_SUBFOLDER') . '/task/update', array('controller' => 'TaskController', 'method'=>'updateAction')));