<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

// Route system
$routes = new RouteCollection();

$routes->add('login', new Route('/login', array('controller' => 'UserController', 'method'=>'loginAction'), []));
$routes->add('homepage', new Route('/', array('controller' => 'IndexController', 'method'=>'indexAction'), []));

$routes->add('task', new Route(constant('URL_SUBFOLDER') . '/task/{id}', array('controller' => 'TaskController', 'method'=>'showAction'), array('id' => '[0-9]+')));
$routes->add('taskList', new Route(constant('URL_SUBFOLDER') . '/taskList', array('controller' => 'TaskController', 'method'=>'listAction'), []));
$routes->add('addTask', new Route(constant('URL_SUBFOLDER') . '/addTask', array('controller' => 'TaskController', 'method'=>'addAction'), []));

$routes->add('notice', new Route(constant('URL_SUBFOLDER') . '/notice/{id}', array('controller' => 'NoticeController', 'method'=>'showAction'), array('id' => '[0-9]+')));
$routes->add('noticeList', new Route(constant('URL_SUBFOLDER') . '/noticeList', array('controller' => 'NoticeController', 'method'=>'listAction'), []));
$routes->add('addNotice', new Route(constant('URL_SUBFOLDER') . '/addNotice', array('controller' => 'NoticeController', 'method'=>'addAction'), []));
$routes->add('noticeMarkAsRead', new Route(constant('URL_SUBFOLDER') . '/notice/mark_as_read/{id}', array('controller' => 'NoticeController', 'method'=>'markAsRead'), []));

$routes->add('message', new Route(constant('URL_SUBFOLDER') . '/message/{id}', array('controller' => 'MessageController', 'method'=>'showAction'), array('id' => '[0-9]+')));
$routes->add('messages', new Route(constant('URL_SUBFOLDER') . '/messages', array('controller' => 'MessageController', 'method'=>'listAction'), []));
$routes->add('addMessage', new Route(constant('URL_SUBFOLDER') . '/addMessage', array('controller' => 'MessageController', 'method'=>'addAction'), []));
$routes->add('messageMarkAsRead', new Route(constant('URL_SUBFOLDER') . '/message/mark_as_read/{id}', array('controller' => 'MessageController', 'method'=>'markAsRead'), []));